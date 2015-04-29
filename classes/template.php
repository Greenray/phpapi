<?php
/**
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @author    David Casado Martínez <tokkara@gmail.com>
 * @link      http://www.simphple.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      classes/template.php
 * @package   template
 */

class template {

    /** @var errors Template errors */
    public $errors = '';

    /** @var integer Code line parsed */
    private $line;

    /** @var string Format for names and function names */
    private static $name = "[_a-zA-Z][_a-zA-Z0-9]*";

    /** @var array Template variables */
    private $vars = [];

    /** Constructor. */
    public function __construct() {
        $this->line = 0;
    }

    /**
     * Checks if variable is empty.
     * This method can be used in the template.
     *
     * @return boolean
     */
    private function _empty() {
        $args = func_get_args();
        return in_array(TRUE, $args);
    }

    /**
	 * Trasnform the "for" structure in php code.
     *
	 * @param string  $name "For" name.
	 * @param string  $code Code for "for" structure.
	 * @return string       Php code.
	 */
	private function _for($name, $code){
		$arr = '$'.$name;
		$cur = '$'.$name.'_cur';
		$max = '$'.$name.'_max';
		$var = '$'.$name.'_var';
		$code = $this->toPhp($code);

		return	'<?php '.$arr.'=array_values('.$code.');'.$max.'=sizeof('.$arr.');'.
				'for('.$cur.'=0; '.$cur.'<'.$max.'; '.$cur.'++):'.$var.'='.$ary.'['.$cur.'];?>';
	}

    /**
     * Trasnforms the "foreach" structure in php code.
     * The template is:
     * <!-- FOREACH var = $array -->
     *    <div>$var.index</div>
     * <!-- ENDFOREACH -->
     *
     * @param  string $name Foreach name
     * @param  string $code Code for 'foreach' structure
     * @return string       Php code
     */
    private function _foreach($name, $code) {
        $arr  = '$'.$name;
        $cur  = '$'.$name.'_cur';
        $var  = '$'.$name.'_var';
        $code = $this->toPhp($code);

        return '<?php '.$arr.'=array_values('.$code.');'.'foreach('.$arr.' as '.$cur.'):'.$var.'='.$cur.';?>';
    }

    /**
     * Transform a function in php code.
     *
     * @param  string $func Function name
     * @param  string $args String with all arguments of the function
     * @return string       Php code
     */
    private function _function($func, $args) {
        if (!$func)
            return '('.$args.')';

        switch ($func) {
            case 'isset':
                $args = str_replace('VARIABLE', 'VARIABLE_ISSET', $args);
                return '$this->_isset('.$args.')';

            case 'empty':
                $args = str_replace('VARIABLE', 'VARIABLE_EMPTY', $args);
                return '$this->_empty('.$args.')';

            case 'array':
                return 'array('.$args.')';

            default:
                return;
        }
    }

    /**
     * Transforms the 'if' structure in php code.
     * The templates are:
     * <!-- IF !empty($var) -->
     *    <div>$var</div>
     * <!-- ENDIF -->
     *
     * <!-- IF isset($var) -->
     *    <div>$var</div>
     * <!-- ELSE -->
     *    <div>"$var" is not set</div>
     * <!-- ENDIF -->
     *
     * @param  string  $code   Code for 'if' structure
     * @param  boolean $elseif Flag indicating if the structure is 'if' or 'elseif'
     * @return string          Php code
     */
    private function _if($code, $elseif) {
        $else = ($elseif) ? 'else' : '';
        return '<?php '.$else.'if('.$this->toPhp($code).'): ?>';
    }

    /**
     * Checks if variable is empty.
     * This method can be used in the template.
     *
     * @return boolean
     */
    private function _isset() {
        return !in_array(FALSE, func_get_args());
    }

    /**
     * Parse the code (HTML & template) and transform the template code in php code.
     *
     * @param  phpapi    &$phpapi  Reference the application object
     * @param  string    $template Template file or HTML code
     * @throws Exception "Failed to open file"
     * @return string              Php code and HTML code
     */
    public function parse(&$phpapi, $template) {
        $code = $template;
        $tpl = TEMPLATES.$phpapi->options['generator'].DS.$template.'.tpl.php';
        if (file_exists($tpl)) {
            $code = file_get_contents($tpl);
        } else {
            $tpl = TEMPLATES.$phpapi->options['generator'].DS.$phpapi->doclet.DS.$template.'.tpl.php';
            if (file_exists($tpl)) {
                $code = file_get_contents($tpl);
            }
        }
        #
        # Extract the template code and parse it.
        #
        $lines   = $php_lines = [];
        $lines   = explode(LF, $code);
        $lines_i = sizeof($lines);
        unset($code);

        do {
            $php_lines[$this->line] = $this->parse_line($lines[$this->line]);
            $this->line++;
        } while ($this->line < $lines_i);

        $code = preg_replace('#\[^;]?>([\s]*)<\?php#', '$1', implode(LF, $php_lines));
        $code = preg_replace_callback("#\{(.*?)\}#is",   [&$this, 'value'],     $code);
        $code = preg_replace_callback("#\[__(.*?)\]#is", [&$this, 'translate'], $code);
        #
        # Execute intermediate php code
        #
        ob_start();
        if (!eval('?>'.$code.'<?php return TRUE; ?>')) {
            $this->errors = $this->errors.ob_get_clean();
            file_put_contents('errors.txt', $this->errors);
        }
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    /**
     * Parse a line of code.
     *
     * @param  string $codeline Code line
     * @return return           Template Code transformed in php code
     */
    private function parse_line($codeline) {
        if (!trim($codeline)) {
            return $codeline;
        }
        #
        # Get html comments and key structures.
        #
        $html     = [];
        $lines    = 0;
        $search   = '#(<!--.*?(?:(\'|\\\\*")(.*?)(?<!\\\\)\2.*?)*?-->)#e';
        $replace  = '(($html[$lines]=\'$1\')&&FALSE).\';;;HTML_COMMENT_\'.($lines++).\';;;\'';
        $codeline = preg_replace($search, $replace, $codeline);
        #
        # Transform the html comments in php code
        #
        $search = [
            '#<!-- IF (.+?) -->#e',
            '#<!-- ELSEIF (.+?) -->#e',
            '#<!-- ELSE -->#',
            '#<!-- ENDIF -->#',
            '#<!-- SWITCH (.+) CASE (.+) -->#e',
            '#<!-- ENDSWITCH -->#',
            '#<!-- CASE (.+?) -->#e',
            '#<!-- DEFAULT -->#',
            '#<!-- BREAK -->#',
            '#<!-- FOREACH ('.self::$name.')[\t ]*=[\t ]*(.+?) -->#e',
            '#<!-- ENDFOREACH -->#',
            '#<!-- EXIT -->#',
            '#<!-- CONTINUE -->#',
            '#<!-- FOR (.+?) -->#e'
        ];

        $replace = [
            '$this->_if(\'$1\', FALSE)',
            '$this->_if(\'$1\', TRUE)',
            '<?php else:?>',
            '<?php endif; ?>',
            '$this->_switch(\'$1\', \'$2\')',
            '<?php endswitch; ?>',
            '$this-><?php case $this->toPhp(\'$1\'): ?>',
            '<?php default: ?>',
            '<?php break; ?>',
            '$this->_foreach(\'$1\', \'$2\')',
            '<?php endforeach; ?>',
            '<?php return TRUE; ?>',
            '<?php continue; ?>',
            '<?php endfor; ?>'
        ];

        for ($i = 0; $i < $lines; $i++) {
            $html[$i] = preg_replace($search, $replace, $html[$i]);
        }

        $keys     = [];
        $keys_i   = 0;
        $search   = '#\{([^\n\r{]*?(?:(\'|\\\\*")(?:.*?)(?<!\\\\)\2.*?)*?)\:([a-zA-Z]*)\}#e';
        $replace  = '(($keys[$keys_i]=array(\'$1\', \'$3\'))&&FALSE).\'KEY_STRUCTURE_\'.($keys_i++).\'\'';
        $codeline = preg_replace($search, $replace, $codeline);

        for ($i = 0; $i < $keys_i; $i++) {
            $keys[$i] = '<?php echo '.$this->toPhp($keys[$i][0]).'; ?>';
        }
        $codeline = preg_replace($search, $replace, $codeline);

        $search   = '#(\$(?:'.self::$name.'\.)?'.self::$name.')#e';
        $replace  = '\'<?php echo \'.($this->toPhp(\'$1\')).\'; ?>\'';
        $codeline = preg_replace($search, $replace, $codeline);
        #
        # Transform the key in php code
        #
        $search  = ['#;;;HTML_COMMENT_([0-9]+);;;#e', '#KEY_STRUCTURE_([0-9]+)#e'];
        $replace = ['$html[$1]', '$keys[$1]'];

        return preg_replace($search, $replace, $codeline);
    }

    /**
     * Adds a variable to the template framework.
     *
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     */
    public function set($name, $value = '') {
        if (is_array($name))
             $this->vars = array_merge($this->vars, $name);
        else $this->vars[$name] = $value;
    }

    /**
     * Store a template code string in array.
     *
     * @param  array  $strings Array used for store the string
     * @param  string $quot    Type of quote (' or ")
     * @param  string $string  String stored in array
     * @return string          Empty string
     */
    private function store_string(&$strings, $quot, $string) {
        #
        # Delete the \ character
        #
        $quot = strlen($quot) > 1 ? substr($quot, -1) : $quot;
        #
        # Delete the var parser.
        #
        if ($quot !== "'") {
            $string = preg_replace('#(\\\*)\$#e', '\'$1\'.(strlen(\'$1\')%2!=0? \'$\': \'\\\$\')', $string);
        }
        $strings[] = [$quot, str_replace('\\\\"', '"', $string)];
        return '';
    }

    /**
     * Transforms the 'switch' structure in php code.
     *
     * @param  string  $code_switch Code for the 'switch' structure
     * @param  unknown $code_case   Code for the first 'case' structure
     * @return string               Php code
     */
    private function _switch($code_switch, $code_case) {
        $code_switch = $this->toPhp($code_switch);
        $code_case   = $this->toPhp($code_case);
        return '<?php switch('.$code_switch.'): case '.$code_case.': ?>';
    }

    /**
     * Trasnforms a template basic code(function, strings, variables & operators) in php code.
     *
     * @param  string $code Template basic code
     * @return string       Php code
     */
    private function toPhp($code) {
        #
        # Transform the strings in key
        #
        $strings = [];
        $search  = '#(\'|\\\\*")(.*?)(?<!\\\\)\1#e';
        $replace = '($this->store_string($strings, \'$1\', \'$2\')).\'STRING\'';
        $code    = preg_replace($search, $replace, $code);
        #
        # Transform variables in key
        #
        $vars    = [];
        $vars_i  = 0;
        $search  = '#\$(?:('.self::$name.')\.)?('.self::$name.')#e';
        $replace = '(($vars[$vars_i]=array(\'$1\', \'$2\'))&&FALSE).\'VARIABLE_\'.($vars_i++).\'\'';
        $code    = preg_replace($search, $replace, $code);
        #
        # Transform functions in key
        #
        $functions   = [];
        $functions_i = 0;
        $search      = '#('.self::$name.'[ \t]*)?\(([^(]*?)\)#e';
        $replace     = '(($functions[$functions_i] = array(\'$1\',\'$2\'))&&FALSE).\'FUNCTION_\'.($functions_i++).\'\'';

        while (preg_match($search, $code)) {
            $code = preg_replace($search, $replace, $code);
        }
        #
        # Transform keys in php functions
        #
        $search  = '#FUNCTION_([0-9]+)#e';
        $replace = '$this->_function($functions[$1][0], $functions[$1][1])';

        while (preg_match($search, $code)) {
            $code = preg_replace($search, $replace, $code);
        }
        #
        # Transform the template keys in Php vars
        #
        $search = [
            '#VARIABLE_ISSET_([0-9]+)#e',
            '#VARIABLE_EMPTY_([0-9]+)#e',
            '#VARIABLE_([0-9]+)#e'
        ];

        $replace = [
            '$this->toPhpVar($vars[$1][0], $vars[$1][1], \'isset\')',
            '$this->toPhpVar($vars[$1][0], $vars[$1][1], \'empty\')',
            '$this->toPhpVar($vars[$1][0], $vars[$1][1])'
        ];

        $code = preg_replace($search, $replace, $code);
        #
        # Transform the template keys in Php vars
        #
        $strings_i = 0;
        $search    = '#STRING#e';
        $replace   = '$strings[$strings_i][0].$strings[$strings_i][1].$strings[$strings_i++][0]';
        return preg_replace($search, $replace, $code);
    }

    /**
     * Transform a template variable in a php variable.
     *
     * @param  string $prefix   Prefix of a template variable
     * @param  string $name     Name of a template variable
     * @param  string $function String indicating if this variable will use in a special function (isset or empty)
     * @return string           Php variable
     */
    private function toPhpVar($prefix, $name, $function = '') {
        if (($name === 'LINE') || ($name === 'FILE')) {
            if ($function === 'isset') return 'TRUE';
            if ($function === 'empty') return 'FALSE';

            return $name === 'LINE' ? '$this->line' : '$this->file';
        }

        $error  = '\'Undefined $%1$s\'';
        $result = (($function === 'isset') || ($function === 'empty')) ? $function.'(%2$s)' : '(isset(%2$s)?%3$s:'.$error.')';
        #
        # Simple variable
        #
        if (!$prefix) {
            $var = '$this->vars[\''.$name.'\']';
            return sprintf($result, $name, $var, $var);
        }
        #
        # Foreach variables.
        #
        if ($prefix) {
            switch ($name) {
                case '_CUR_':
                    $var = '$'.$prefix.'_cur';
                    return sprintf($result, $prefix.'._CUR_', $var, $var);

                case '_EVEN_':
                    $var_iss = '$'.$prefix.'_cur';
                    $expr    = '$'.$prefix.'_cur%2!=0';
                    return sprintf($result, $prefix.'._EVEN_', $var_iss, $expr);

                case '_FIRST_':
                    $var_iss = '$'.$prefix.'_cur';
                    $expr    = '$'.$prefix.'_cur==0';
                    return sprintf($result, $prefix.'._FIRST_', $var_iss, $expr);

                case '_LAST_':
                    $var_iss = '$'.$prefix.'_cur';
                    $expr    = '$'.$prefix.'_cur+1==$'.$prefix.'_max';
                    return sprintf($result, $prefix.'._LAST_', $var_iss, $expr);

                case '_MAX_':
                    $var = '$'.$prefix.'_max';
                    return sprintf($result, $prefix.'._MAX_', $var, $var);

                case '_VAL_':
                    $var = '$'.$prefix.'_var';
                    return sprintf($result, $prefix.'_VAL_', $var, $var);

                default:
                    $var      = '$'.$prefix.'_var';
                    $var_name = $var.'[\''.$name.'\']';
                    if ($function == 'empty')
                        return 'empty('.$var_name.') || !is_array('.$var.')';

                    $iss = 'isset('.$var_name.')&&is_array('.$var.')';
                    return $function == 'isset' ? '('.$iss.')' : '('.$iss.'?'.$var_name.':'.sprintf($error, $prefix.'.'.$name).')';
            }
        }
    }

    /**
     * Localization.
     * <pre>
     * The template is:
     *   [__string]
     * </pre>
     * Array variable $matches contains:
     *  - $matches[0] = part of template between control structures including them;
     *  - $matches[1] = part of template between control structures excluding them.
     *
     * @param  array  $matches Matches for control structure "if"
     * @return string          Parsed string
     */
    private function translate($matches) {
        return str_replace($matches[0], __($matches[1]), $matches[0]);
    }

    /**
     * Replaces constants and variables with their values.
     * <pre>
     * The template is:
     *   {CONST} - global constant
     * </pre>
     *
     * @param  array  $matches Matches for control structure "if"
     * @return string          Parsed string
     */
    private function value($matches) {
        if (defined($matches[1])) {
            return str_replace($matches[0], constant($matches[1]), $matches[0]);
        }
    }
}
