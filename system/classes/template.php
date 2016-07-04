<?php
/**
 * Templates parser.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 - 2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/classes/template.php
 * @package   template
 * @overview  Completely separated from php code.
 *            Handles special tags and replaces them with commands of the php interpreter.
 *            It is able recursively execute functions and directives:
 *            FOREACH, IF, ELSE, ELSEIF, SWITCH, CASE, BREAK, DEFAULT, INCLUDE, CONTINUE.
 */

class template {

    /** @var string Template content */
    private $code = '';

	/** @var string Name of the template file that is currently executing */
	private $file = '';

	/** @var integer Current line of the template content */
	private $line = 0;

    /** @var array Template statements */
    private $statements = [
        'BREAK'    => '<?php break;?>',
        'CONTINUE' => '<?php continue;?>',
        'DEFAULT'  => '<?php default: ?>',
        'ELSE'     => '<?php } else { ?>',
        'END'      => '<?php } ?>'
    ];
    /** @var array Temporary variable for code processing */
    private $temp = [];

    /** @var array Locales */
    private $translations = [];

    /** @var array Temporary variables */
    private $vars = [];

    /**
     * Class constructor.
     *
     * @param array $template Template file
     * @param array $options  Template parser options
     */
    public function __construct() {}

    /**
     * Reads template.
     *
     * @param string $template Template filename with full path
     * @param phpapi &$phpapi  Reference to the application object
     */
    private function getTemplate(&$phpapi, $template) {
        $this->file = $template;
        $template   = basename($template).'.tpl.php';
        if (file_exists(TEMPLATES.$template)) {
            $this->code = file_get_contents(TEMPLATES.$template);
        } else if (file_exists(TEMPLATES.$phpapi->doclet.DS.$template)) {
            $this->code = file_get_contents(TEMPLATES.$phpapi->doclet.DS.$template);
        }
        #
        # Remove php code from template
        #
        $this->code = preg_replace("#<\?php(.*?)\?>\n{1,}#is", '', $this->code);

        if ($phpapi->options['language'] !== 'english') {
            $lang = [];

            include LOCALES.$phpapi->options['language'].'.php';

            $this->translations = $lang;
        }
    }

    /**
     * Sets a variables (plain or array) of the template.
     *
     * @param mixed $var   Variable name
     * @param mixed $value Variable value
     */
    public function set($var, $value = '') {
        if (is_array($var))
             $this->vars = array_merge($this->vars, $var);
        else $this->vars[$var] =$value;
    }

    /**
     * Parses the code (HTML & template) and transform the template code in php code.
     *
     * @return string Parsed template
     */
    public function parse(&$phpapi, $template) {
        $this->getTemplate($phpapi, $template);
        #
        # Extract and parse template
        #
        $this->line = 0;
        $tpl   = [];
        $lines = explode(LF, $this->code);
        $count = sizeof($lines);

        do {
            $tpl[$this->line] = '<?php $this->line='.($this->line + 1).';?>'.$this->parseLine($lines[$this->line]);
            ++$this->line;
        } while($this->line < $count);

        $code = preg_replace('#\[^;]?>([\s]*)<\?php#', '$1', implode(LF, $tpl));
        $code = preg_replace_callback("#\{([\-\w]+)\}#is",  [&$this, 'value'], $code);
        #
        # Execute php code
        #
        ob_start();
        eval('?>'.$code.'<?php return TRUE; ?>');
        $result = ob_get_contents();
        $result = preg_replace_callback("#__(.*?)__#is", [&$this, 'translate'], $result);
        ob_end_clean();
return $result;
//        return $this->compact($result);
    }

    /**
     * Parses a line of code.
     *
     * @param  string $code Line of code
     * @return string       Code transformed in php code
     */
    private function parseLine($code) {
        #
        # Empty line
        #
        if (!trim($code)) return $code;
        #
        # Get template expressions
        #
        preg_match_all('#<!-- ([A-Z]+)+? *([ \S]*?) -->#', $code, $match, PREG_SET_ORDER);
        if (!empty($match)) {
            foreach($match as $key => $expr) {
                $tmp = '#:'.$this->randomString(6).':#';
                if (empty($expr[2])) {
                    $this->temp[$tmp] = str_replace($expr[0], $this->statements[$expr[1]], $expr[0]);
                } else {
                    switch($expr[1]) {
                        case 'IF':
                            $this->temp[$tmp] = str_replace($expr[0], $this->_if($expr[2], FALSE), $expr[0]);
                        break;
                        case 'ELSEIF':
                            $this->temp[$tmp] = str_replace($expr[0], $this->_if($expr[2], TRUE), $expr[0]);
                        break;
                        case 'ELSE':
                            $this->temp[$tmp] = str_replace($expr[0], '<?php } else { $this->line='.($this->line + 1).';?>', $expr[0]);
                        break;
                        case 'FOREACH':
                            $this->temp[$tmp] = str_replace($expr[0], $this->_foreach($expr[2]), $expr[0]);
                        break;
                        case 'SWITCH':
                            $this->temp[$tmp] = str_replace($expr[0], $this->_switch($expr[2], FALSE), $expr[0]);
                        break;
                        case 'CASE':
                            $this->temp[$tmp] = str_replace($expr[0], $this->_case($expr[2], FALSE), $expr[0]);
                        break;
                    }
                }
                $code = str_replace($expr[0], $tmp, $code);
            }
        }
        #
        # Transform template variables to php code
        #
        $code = preg_replace_callback(
            '#\[*\$(?:[\w]+\.)?[\w]*[^_\W]\]*#',
            function($match) {
                return '<?php echo '.$this->createVar($match[0]).';?>';
            },
            $code
        );
        #
        # Transform saved template expressions to php code
        #
        foreach($this->temp as $key => $value) {
            $code = str_replace($key, $value, $code);
        }
        $this->temp = [];
        return $code;
    }

    /**
     * Creates variable.
     *
     * @param  string $code Template basic code
     * @return string       PHP code
     */
    private function createVar($code) {
        $code = str_replace(['[', ']'], '', $code);
        return preg_replace_callback(
            '#\$(?:([\w]*)\.)?([\w]*)#',
            function($match) {
                if (empty($match[1]))
                     return '$this->vars[\''.$match[2].'\']';
                else return '$'.$match[1].'[\''.$match[2].'\']';
            },
            $code
        );
    }

    /**
     * Trasnforms the FOREACH structure in php code.
     *
     * @param  string $param Parameters for FOREACH structure
     * @return string        PHP code
     */
    private function _foreach($param) {
        if (strpos($param, '.') !== FALSE) {
            $values = explode('.', $param);
            $array  = $values[0].'[\''.$values[1].'\']';
            $param  = '$'.$values[1];
        } else {
            $array = $this->createVar($param);
        }
        return '<?php foreach('.$array.' as '.$param.') { ?>';
    }

    /**
     * Transforms the IF structure in php code.
     *
     * @param  string  $code   Code for IF structure
     * @param  boolean $elseif Flag indicating if the structure is IF or ELSEIF
     * @return string          PHP code
     */
    private function _if($code, $elseif) {
        $code = $this->createVar($code);
        $else = '';
        if ($elseif) {
            $else = 'else';
            $code = '($this->line='.($this->line + 1).')&&'.$code;
        }
        return '<?php '.$else.'if ('.$code.') { ?>';
    }

    /**
     * Transforms the SWITCH structure in php code.
     *
     * @param  string $param Code for the SWITCH structure
     * @return string        PHP code
     */
    private function _switch($param) {
        $param = $this->createVar($param);
        return '<?php switch('.$param.') { ?>';
    }

    /**
     * Transforms the CASE structure in php code.
     *
     * @param  string $param Parameter for CASE structure
     * @return string        PHP code
     */
    private function _case($param) {
        return '<?php case (($this->line='.($this->line + 1).')&&FALSE).'.$this->createVar($param).': ?>';
    }

    /**
     * Localization.
     *
     * Array variable $matches contains:
     *  - $matches[0] = part of template between control structures including them;
     *  - $matches[1] = part of template between control structures excluding them.
     *
     * @param  array  $match Matches for translation
     * @return string        Parsed string
     */
    private function translate($match) {
        if (!empty($this->translations)) {
            return str_replace($match[0], (empty($this->translations[$match[1]]) ? $match[1] : $this->translations[$match[1]]), $match[0]);
        }
        return trim($match[0], '__');
    }

    /**
     * Replaces constants and global variables with their values.
     *
     * @param  array  $match Matches for constants
     * @return string        Parsed string
     */
    private function value($match) {
        if (defined($match[1])) {
            return str_replace($match[0], constant($match[1]), $match[0]);
        }
    }

    /**
     * Compacts compiled template.
     *
     * @param  string $data Compiled template
     * @return string       Code without newlines and extra spaces
     */
    private function compact($data) {
        if ($this->options['compact']) {
            #
            # Remove two or more consecutive spaces
            #
            $data = preg_replace('# {2,}#', '', $data);
            #
            # Remove newline characters and tabs
            #
            return str_replace(["\r\n", "\r", "\n", "\t"], '', $data);
            return $data;
        }
        return $data;
    }

    /**
     * Generates random string.
     *
     * @param  integer $num_chars The lenght of string to generate
     * @return string             Generated string
     */
    private function randomString($num_chars) {
        $chars = [
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            '0','1','2','3','4','5','6','7','8','9','_'
        ];
        $max_chars = sizeof($chars) - 1;
        $result = '';
        for ($i = 0; $i < $num_chars; $i++) {
            $result .= $chars[mt_rand(0, $max_chars)];
        }
        return $result;
    }
}
