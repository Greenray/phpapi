<?php
/**
 * php tokenizer and parser.
 * Particularly the packages, classes and options specified by the user.
 * It is the root of the parsed tokens and is passed to the doclet to be formatted into output.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      classes/phpapi.php
 * @package   phpapi
 * @overview  Main program package.
 *            It sets global variables, loads classes, tokenizes and parses php files.
 *            After that it creates descriptions of the program elements and calls doclet to create documentation pages.
 */
class phpapi {

    /** @var string Specifies the name of the class that starts the doclet used in generating the documentation */
    public $doclet = '';

    /** @var array Files to parse */
    public $files = [];

    /** @var array Options from config file */
    public $options = [];

    /** @var string Path phpapi is running from */
    public $path = '.';

    /** @var integer Number of parsed source files */
    public $sourceIndex = 0;

    /** @var string Directory containing files for parsing */
    public $source = ['./'];

    /** @var integer Time in microseconds of the start of execution */
    private $startTime = 0;

    /**
     * Constructor.
     * Parses ini file and creates list of files for processing.
     *
     * @param string $config phpai configuration file (Default = 'phpapi.ini')
     */
    public function __construct($config = 'phpapi.ini') {
        $this->startTime = $this->getTime();
        $this->path      = dirname(dirname(__FILE__));

        if (is_file($config)) {
            $this->options = parse_ini_file($config);
            if (count($this->options) === 0) {
                $this->error('Cannot parse configuration file "'.$config.'"');
                exit;
            }
        } else {
            $this->error('Cannot find configuration file "'.$config.'"');
            exit;
        }
        #
        # Set phpapi options
        #
        if (!empty($this->options['source'])) {
            $this->source = [];
            foreach (explode(',', $this->options['source']) as $path) {
                $this->source[] = $this->fixPath($path, getcwd());
            }
        }
        $this->options['destination'] = $this->fixPath($this->source[0].$this->options['destination']);

        if (!empty($this->options['files']))
             $files = explode(',', $this->options['files']);
        else $files = ['*.php'];

        if (!empty($this->options['ignore'])) {
            $this->options['ignore'] = explode(', ', $this->options['ignore']);
        }
        $this->doclet = $this->options['doclet'];

        $this->verbose('Searching for files to parse...');

        $this->files = [];
        foreach ($this->source as $path) {
            $this->files[$path] = $this->getFiles($files, $path);
        }
        if (count($this->files) === 0) {
            $this->error('Cannot find any files to parse');
            exit;
        }
    }

    /**
     * Creates a tag.
     * This method first tries to load a Taglet for the given tag name, upon failing it
     * then tries to load a phpapi specialised tag class (e.g. classes/paramtag.php),
     * if it still has not found a tag class it uses the standard tag class.
     *
     * @param  string  $name  Name of the tag
     * @param  string  $text  Contents of the tag
     * @param  array   &$data Reference to the doc comment data array
     * @param  rootDoc $root  Reference to the root element
     * @return tag            Tag object
     */
    function &createTag($name, $text, &$data, &$root) {
        $class = substr($name, 1);
        if ($class) {
            $tagFile = TAGLETS.substr($name, 1).'.php';
            if (is_file($tagFile)) {
                #
                # Load taglet for this tag.
                #
                if (!class_exists($class)) require_once($tagFile);
                $tag = &new $class($text, $data, $root);
                return $tag;

            } else {
                $tagFile = TAGLETS.$class.'Tag.php';
                if (is_file($tagFile)) {
                    #
                    # Load class for this tag.
                    #
                    $class .= 'Tag';
                    if (!class_exists($class)) require_once($tagFile);
                    $tag = &new $class($text, $data, $root);
                    return $tag;

                } else {
                    #
                    # Create standard tag.
                    #
                    $tag = &new tag($name, $text, $root);
                    return $tag;
                }
            }
        }
    }

    /**
     * Loads and runs the doclet.
     *
     * @param rootDoc &$rootDoc Reference to root document
     */
    public function execute(&$rootDoc) {

        require DOCLETS.$this->options['generator'].DS.'htmlWriter.php';
        require DOCLETS.$this->options['generator'].DS.'overviewSummaryWriter.php';
        require DOCLETS.$this->options['generator'].DS.'packageWriter.php';
        require DOCLETS.$this->options['generator'].DS.'classWriter.php';
        require DOCLETS.$this->options['generator'].DS.'functionWriter.php';
        require DOCLETS.$this->options['generator'].DS.'globalWriter.php';
        require DOCLETS.$this->options['generator'].DS.'indexWriter.php';
        require DOCLETS.$this->options['generator'].DS.'deprecatedWriter.php';
        require DOCLETS.$this->options['generator'].DS.'todoWriter.php';

        $docletFile = DOCLETS.$this->options['generator'].DS.$this->doclet.DS.$this->doclet.'.php';
        if (is_file($docletFile)) {

            $this->verbose('Loading doclet "'.$this->doclet.'"');

            require_once $docletFile;

            $formatter = FORMATTERS.$this->options['formatter'].'.php';
            if (is_file($formatter)) {

                $this->verbose('Loading formatter "'.$this->options['formatter'].'"');

                require_once $formatter;
                $doclet = &new $this->doclet($rootDoc, new $this->options['formatter']);

            } else {
                $this->error('Cannot find formatter "'.$formatter.'"');
                exit;
            }

        } else $this->error('Cannot find doclet "'.$docletFile.'"');

        $this->verbose('Done ('.round($this->getTime() - $this->startTime, 2).' seconds)');
    }

    /**
     * Gets element name from the token list.
     *
     * @param  array   &$tokens Reference to the array of tokens
     * @param  integer $key     Key to start searching from
     * @return string           Name of the program element
     */
    private function getElementName(&$tokens, $key) {
        $name = '';
        $key++;
        while (
            $tokens[$key] && (
                $tokens[$key] === '&' || (
                    isset($tokens[$key][0]) && isset($tokens[$key][1]) && (
                        $tokens[$key][0] === T_WHITESPACE ||
                        $tokens[$key][0] === T_STRING ||
                        $tokens[$key][0] === T_NS_SEPARATOR
                    )
                )
            )
        ) {
            if (isset($tokens[$key][1])) $name .= $tokens[$key][1];
            $key++;
        }
        return trim($name);
    }

    /**
     * Gets the current time in microseconds.
     *
     * @return integer Current time
     */
    private function getTime() {
        $microtime = explode(' ', microtime());
        return $microtime[0] + $microtime[1];
    }

    /**
     * Builds a complete list of files to parse.
     * Expands out wildcards and traverse directories if asked to.
     * This function is recursive.
     *
     * @param  array  $files Filenames or wildcards
     * @param  string $dir   Directory to scan
     * @return array         List of files to parse
     */
    private function getFiles($files, $dir) {
        $list = [];
        if (!$dir) {
            return $list;
        }

        $dir = $this->fixPath($dir);
        foreach ($files as $filename) {
            $globResults = glob($dir.$filename);
            if ($globResults) {
                foreach ($globResults as $file) {
                    $path_parts = pathinfo($file);
                    if (!in_array($path_parts['basename'], $this->options['ignore'])) {
                        $list[] = $file;
                    }
                }
            } elseif (!$this->options['subDirs']) $this->error('Cannot find file "'.$filename.'"');
        }
        #
        # Recurse into subdir
        #
        if ($this->options['subDirs']) {
            $globResults = glob($dir.'*', GLOB_ONLYDIR);
            if ($globResults) {
                foreach ($globResults as $dirName) {
                    $parts = explode(DS, $dirName);
                    $okay = TRUE;
                    foreach($parts as $part) {
                        if (in_array($part, $this->options['ignore'])) {
                            $okay = FALSE;
                        }
                    }
                    if ($okay && (GLOB_ONLYDIR || is_dir($dirName))) $list = array_merge($list, $this->getFiles($files, $dirName));
                }
            }
        }
        return $list;
    }

    /**
     * Gets the type of the variable.
     *
     * @param  mixed $var Variable
     * @return string     Type of the variable
     */
    private function getType($var) {
        if ((substr($var, 0, 5) === 'array') || ((substr($var, 0, 1) === '[') && (substr($var, -1, 1)=== ']'))) {
            return 'array';
        }
        if (is_numeric($var)) {
            return 'integer';
        } elseif ((strtolower($var) === 'TRUE') || (strtolower($var) === 'FALSE')) {
            return 'boolean';
        } elseif (is_string($var)) {
            return 'string';
        }
        return 'mixed';
    }

    /**
     * Addss a trailing slash to a path if it does not have one.
     *
     * @param  string $path Path to fix
     * @return string       Fixed path
     */
    public function fixPath($path) {
        return (substr($path, -1, 1) !== DS && substr($path, -1, 1) !== '\\') ? $path.DS : $path;
    }

    /**
     * Does the given element name conform to the format that is used for private elements?
     *
     * @param  string $name Name to check
     * @return boolean
     */
    public function hasPrivateName($name) {
        return substr($name, 0, 1) === '_';
    }

    /**
     * Merges data of the superclass.
     * This function is recursive.
     *
     * @param rootDoc &$rootDoc Reference to the root element
     * @param string  $parent   Superclass (Default = NULL)
     */
    public function mergeSuperClassData(&$rootDoc, $parent = NULL) {
        $classes = &$rootDoc->classes();
        foreach ($classes as $name => $class) {
            if ($class->superclass === $parent) {
                $class->mergeSuperClassData();
                $this->mergeSuperClassData($rootDoc, $class->name);
            }
        }
    }

    /**
     * Gets next token of a certain type from token array.
     *
     * @param  array   &$tokens   Reference to the array of tokens to search
     * @param  integer $key       Key to start searching from
     * @param  integer $whatToGet Type of token to look for
     * @param  integer $maxDist   Optional max distance from key to look at (default is 0 for all)
     * @return array|boolean      Value of found token or FALSE
     */
    private function next(&$tokens, $key, $whatToGet, $maxDist = 0) {
        $start = $key;
        $key++;
        if (!is_array($whatToGet)) $whatToGet = [$whatToGet];
        while (!is_array($tokens[$key]) || !in_array($tokens[$key][0], $whatToGet)) {
            $key++;
            if (!isset($tokens[$key]) || (0 < $maxDist && (($key - $start) > $maxDist))) {
                return FALSE;
            }
        }
        return $tokens[$key][1];
    }

    /**
     * Parses files into tokens and create rootDoc.
     *
     * @return rootDoc Reference to the root element
     */
    public function &parse() {
        $rootDoc = &new rootDoc($this);
        $ii = 0;
        foreach ($this->files as $path => $files) {
            $this->sourceIndex = $ii++;
            foreach ($files as $filename) {
                if ($filename) {
                    $this->verbose('File "'.$filename.'"');
                    $fileString = file_get_contents($filename);
                    if ($fileString !== FALSE) {
                        $fileString = str_replace(["\r\n", "\n\r", "\r", LF], LF, $fileString);
                        #
                        # Remove the empty lines from a file.
                        #
                        $tokens = token_get_all($fileString);
                        #
                        # This array holds data gathered before the type of element is discovered and an object is created for it, including doc comment data.
                        # This data is stored in the object once it has been created and then merged into the objects data fields upon object completion.
                        #
                        $currentData    = [];
                        $currentPackage = $this->options['defaultPackage'];
                        $defaultPackage = $oldDefaultPackage = $currentPackage;
                        $fileData       = [];

                        $currentElement = [];     # Stack of element family, current at top of stack
                        $ce = &$rootDoc;          # Reference element at top of stack

                        $open_curly_braces = FALSE;
                        $in_parsed_string  = FALSE;

                        $lineNumber    = 1;
                        $commentNumber = 0;
                        $numOfTokens   = count($tokens);

                        for ($key = 0; $key < $numOfTokens; $key++) {
                            $token = $tokens[$key];
                            if (!$in_parsed_string && is_array($token)) {
                                $lineNumber += substr_count($token[1], LF);

                                switch ($token[0]) {

                                    case T_DOC_COMMENT:
                                        $currentData = array_merge($currentData, $this->parseDocComment($token[1], $rootDoc));
                                        if ($currentData) {
                                            $commentNumber++;
                                            if ($commentNumber === 1) {
                                                if (isset($currentData['package'])) {
                                                    #
                                                    # Store 1st comment incase it is a file level comment
                                                    #
                                                    $oldDefaultPackage = $defaultPackage;
                                                    $defaultPackage    = $currentData['package'];
                                                    if (isset($currentData['tags']['@overview'])) {
                                                        $rootDoc->packageNamed($currentData['package'], TRUE, $currentData['tags']['@overview']->text);
                                                    }
                                                }
                                                $fileData = $currentData;
                                            }
                                        }
                                        break;

                                    case T_CLASS:
                                        $class = &new classDoc($this->getElementName($tokens, $key), $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                        $this->verbose('Found class: '.$class->name);

                                        if (isset($currentData['docComment'])) {
                                            $class->set('docComment', $currentData['docComment']);
                                        }
                                        $class->set('data', $currentData);
                                        if (!empty($currentData['package'])) {
                                            $currentPackage = $currentData['package'];
                                        }
                                        $class->set('package', $currentPackage);
                                        if (isset($currentData['tags']['@overview']))
                                             $parentPackage = &$rootDoc->packageNamed($class->package, TRUE, $currentData['tags']['@overview']->text);
                                        else $parentPackage = &$rootDoc->packageNamed($class->package, TRUE);
                                        $parentPackage->addClass($class);

                                        $class->setByRef('parent', $parentPackage);
                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$class;
                                        $ce = &$class;
                                        break;

                                    case T_INTERFACE:
                                        $interface = &new classDoc($this->getElementName($tokens, $key), $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                        $this->verbose('Found interface: '.$interface->name);

                                        if (isset($currentData['docComment'])) {
                                            $interface->set('docComment', $currentData['docComment']);
                                        }
                                        $interface->set('data', $currentData);
                                        $interface->set('interface', TRUE);
                                        if (!empty($currentData['package'])) {
                                            $currentPackage = $currentData['package'];
                                        }
                                        $interface->set('package', $currentPackage);
                                        $parentPackage = &$rootDoc->packageNamed($interface->package, TRUE);
                                        $parentPackage->addClass($interface);

                                        $interface->setByRef('parent', $parentPackage);
                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$interface;
                                        $ce = &$interface;
                                        break;

                                    case T_TRAIT:
                                        $trait = &new classDoc($this->getElementName($tokens, $key), $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                        $this->verbose('Found trait: '.$trait->name);

                                        if (isset($currentData['docComment'])) {
                                            $trait->set('docComment', $currentData['docComment']);
                                        }
                                        $trait->set('data', $currentData);
                                        $trait->set('trait', TRUE);
                                        if (!empty($currentData['package'])) {
                                            $currentPackage = $currentData['package'];
                                        }
                                        $trait->set('package', $currentPackage);
                                        $parentPackage = &$rootDoc->packageNamed($trait->package, TRUE);
                                        $parentPackage->addClass($trait);

                                        $trait->setByRef('parent', $parentPackage);
                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$trait;
                                        $ce = &$trait;
                                        break;

                                    case T_EXTENDS:
                                        $superClassName = $this->getElementName($tokens, $key);
                                        $ce->set('superclass', $superClassName);
                                        if ($superClass = &$rootDoc->classNamed($superClassName) &&
                                            $commentTag = (isset($superClass->tags['@text'])) ? $superClass->tags['@text'] : NULL) {
                                            $ce->tags['@text'] = &$commentTag;
                                        }
                                        break;

                                    case T_ABSTRACT:
                                        $currentData['abstract'] = TRUE;
                                        break;

                                    case T_CONST:
                                        $currentData['var'] = 'const';
                                        break;

                                    case T_CURLY_OPEN:
                                    case T_DOLLAR_OPEN_CURLY_BRACES:
                                        #
                                        # We must catch this so we don't accidently step out of the current block
                                        #
                                        $open_curly_braces = TRUE;
                                        break;

                                    case T_FINAL:
                                        $currentData['final'] = TRUE;
                                        break;

                                    case T_FUNCTION:
                                        $name   = $this->getElementName($tokens, $key);
                                        $method = &new methodDoc($name, $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());
                                        unset($name);

                                        $msg = "\t".'method : '.$method->name;

                                        if (isset($currentData['docComment'])) $method->set('docComment', $currentData['docComment']);
                                        $method->set('data', $currentData);
                                        $ceClass = get_class($ce);
                                        if ($ceClass === 'rootDoc') {

                                            $msg = 'Found function: '.$method->name;

                                            if (isset($currentData['access']) && $currentData['access'] === 'private') {
                                                $method->access = 'private';
                                            }
                                            if (!empty($currentData['package']))
                                                 $method->set('package', $currentData['package']);
                                            else $method->set('package', $currentPackage);

                                            $method->mergeData();
                                            $parentPackage = &$rootDoc->packageNamed($method->package, TRUE);

                                            if (isset($parentPackage->functions[$method->name])) {
                                                $this->warning(LF.'Found function '.$method->name.' again, overwriting previous version');
                                            }
                                            $parentPackage->functions[$method->name] = &$method;

                                        } elseif ($ceClass === 'classDoc' || $ceClass === 'methodDoc') {
                                            $method->set('package', $ce->package);
                                            if (($method->name === '__construct') || ($method->name === '__destruct') || ($method->name === $ce->name)) {
                                                $method->set('name', $method->name);
                                            } else {
                                                if ($this->hasPrivateName($method->name) || (isset($currentData['access']) && $currentData['access'] === 'private')) {
                                                    $method->access = 'private';
                                                }
                                                $method->mergeData();
                                            }

                                            if (isset($ce->methods[$method->name])) {
                                                $this->warning(LF.'Found method '.$method->name.' again, overwriting previous version');
                                            }
                                            $ce->methods[$method->name] = &$method;
                                        }
                                        $this->verbose($msg).LF;
                                        unset($msg);

                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$method;
                                        $ce = &$method;
                                        unset($method);
                                        break;

                                    case T_IMPLEMENTS:
                                        $interfaceName = $this->getElementName($tokens, $key);
                                        $interface     = &$rootDoc->classNamed($interfaceName);
                                        if ($interface) $ce->set('interfaces', $interface);
                                        break;

                                    case T_INCLUDE:
                                    case T_INCLUDE_ONCE:
                                    case T_REQUIRE:
                                    case T_REQUIRE_ONCE:
                                        $include = $tokens[$key][1];
                                        while (isset($tokens[$key]) && $tokens[$key] !==';') {
                                            $key++;
                                            if (is_array($tokens[$key]))
                                                 $include .= $tokens[$key][1];
                                            else $include .= $tokens[$key];
                                        }
                                        $ce->set('includes', $include);
                                        unset($include);
                                        break;

                                    case T_NAMESPACE:
                                    case T_NS_C:
                                        while ($tokens[++$key][0] !== T_STRING);
                                        $namespace = $tokens[$key++][1];
                                        while ($tokens[$key][0] === T_NS_SEPARATOR) {
                                            $namespace .= $tokens[$key++][1].$tokens[$key++][1];
                                        }
                                        $currentPackage = $defaultPackage = $oldDefaultPackage = $namespace;
                                        $key--;
                                        break;

                                    case T_PRIVATE:
                                        $currentData['access'] = 'private';
                                        break;

                                    case T_PROTECTED:
                                        $currentData['access'] = 'protected';
                                        break;

                                    case T_PUBLIC:
                                        $currentData['access'] = 'public';
                                        break;

                                    case T_STATIC:
                                        $currentData['static'] = TRUE;
                                        break;

                                    case T_STRING:
                                        $value = '';
                                        if ($token[1] === 'define') {
                                            $const = &new fieldDoc($this->next($tokens, $key, T_CONSTANT_ENCAPSED_STRING), $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());
                                            $const->set('final', TRUE);

                                            do {
                                                $key++;
                                            } while (isset($tokens[$key]) && $tokens[$key] !== ',');
                                            $key++;

                                            while (isset($tokens[$key]) && $tokens[$key] !== ')') {
                                                if (is_array($tokens[$key]))
                                                     $value .= $tokens[$key][1];
                                                else $value .= $tokens[$key];
                                                $key++;
                                            }

                                            $value = trim($value);
                                            if (!empty($value)) {
                                                if (strpos($value, '(')) {
                                                    $value = $value.')';
                                                }
                                                $const->set('value', $value);
                                                $const->set('type', new type($this->getType($value), $rootDoc));
                                            }

                                            if (isset($currentData['docComment'])) $const->set('docComment', $currentData['docComment']);

                                            $const->set('data', $currentData);

                                            if (isset($currentData['package']))
                                                 $const->set('package', $currentData['package']);
                                            else $const->set('package', $currentPackage);

                                            $this->verbose('Found global constant '.$const->name.' in package '.$const->package);

                                            $const->mergeData();
                                            $parentPackage = &$rootDoc->packageNamed($const->package, TRUE);
                                            $parentPackage->addGlobal($const);
                                            unset($const);

                                        } elseif (isset($currentData['var']) && $currentData['var'] === 'const') {
                                            #
                                            # Member constant
                                            #
                                            $value = '';
                                            do {
                                                $key++;
                                                if ($tokens[$key] === '=') {
                                                    $name = $this->previous($tokens, $key, [T_VARIABLE, T_STRING]);

                                                } elseif (isset($value) && $tokens[$key] !== ',' && $tokens[$key] !== ';') {

                                                    if (is_array($tokens[$key]))
                                                         $value .= $tokens[$key][1];
                                                    else $value .= $tokens[$key];
                                                } elseif ($tokens[$key] === ',' || $tokens[$key] === ';') {
                                                    if (!isset($name)) $name = $this->previous($tokens, $key, [T_VARIABLE, T_STRING]);
                                                    $const = &new fieldDoc($name, $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());
                                                    unset($name);

                                                    if ($this->hasPrivateName($const->name)) $const->access = 'private';
                                                    $const->set('final', TRUE);
                                                    $value = trim($value);
                                                    if (!empty($value)) {
                                                        $const->set('value', $value);
                                                        $const->set('type', new type($this->getType($value), $rootDoc));
                                                    }

                                                    if (isset($currentData['docComment'])) $const->set('docComment', $currentData['docComment']);
                                                    $const->set('data',    $currentData);
                                                    $const->set('package', $ce->package);
                                                    $const->set('static',  TRUE);

                                                    $this->verbose("\t".'const: '.$const->name);

                                                    $const->mergeData();
                                                    $ce->constants[$const->name] = &$const;
                                                    unset($const);
                                                 }
                                            } while (isset($tokens[$key]) && $tokens[$key] !==';');
                                            $currentData = [];

                                        } elseif (get_class($ce) === 'methodDoc' && $ce->inBody === 0) {
                                            # Function parameter
                                            $typehint = NULL;
                                            do {
                                                $key++;
                                                if (!isset($tokens[$key]))
                                                    break;

                                                if ($tokens[$key] === ',' || $tokens[$key] === ')') {
                                                    unset($parameter);

                                                } elseif (is_array($tokens[$key])) {
                                                    if ($tokens[$key][0] === T_STRING && !isset($parameter)) {
                                                        $typehint = $tokens[$key][1];
                                                    } elseif ($tokens[$key][0] === T_VARIABLE && !isset($parameter)) {
                                                        if ($tokens[$key - 1] === '&') {
                                                            $tokens[$key][1] = $tokens[$key - 1].$tokens[$key][1];
                                                        }
                                                        $parameter = &new fieldDoc($tokens[$key][1], $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                                        $msg = "\t\t".'parameter: '.$parameter->name;

                                                        if (isset($currentData['docComment'])) {
                                                            $parameter->set('docComment', $currentData['docComment']);
                                                        }
                                                        if ($typehint) {
                                                            $parameter->set('type', new type($typehint, $rootDoc));
                                                            $msg .= ' has a typehint of '.$typehint;
                                                        }
                                                        $parameter->set('data', $currentData);
                                                        $parameter->set('package', $ce->package);
                                                        $parameter->mergeData();
                                                        $ce->parameters[$parameter->name] = &$parameter;
                                                        $typehint = NULL;

                                                        $this->verbose($msg);

                                                    } elseif (isset($parameter) && ($tokens[$key][0] === T_STRING || $tokens[$key][0] === T_CONSTANT_ENCAPSED_STRING || $tokens[$key][0] === T_LNUMBER)) {
                                                        $value = trim($tokens[$key][1]);
                                                        if (!empty($value)) {
                                                            $parameter->set('value', $value);
                                                            if (!$typehint) {
                                                               $parameter->set('type', new type($this->getType($value), $rootDoc));
                                                            }
                                                        }
                                                    }
                                                }
                                            } while (isset($tokens[$key]) && $tokens[$key] !==')');

                                            $currentData = [];
                                            unset($value);
                                        }
                                        break;

                                    case T_THROW:
                                        $className = $this->next($tokens, $key, T_STRING);
                                        $class = &$rootDoc->classNamed($className);
                                        if ($class)
                                             $ce->setByRef('throws', $class);
                                        else $ce->set('throws', $className);
                                        break;

                                    case T_USE:
                                        if (get_class($ce) === 'classDoc') {
                                            while ($tokens[++$key][0] !== ';') {
                                                if ($tokens[$key][0] === T_STRING) {
                                                    $className = $tokens[$key][1];
                                                    $class = &$rootDoc->classNamed($className);
                                                    if ($class)
                                                         $ce->setByRef('traits', $class);
                                                    else $ce->set('traits', $className);
                                                }
                                            }
                                        }
                                        break;

                                    case T_VAR:
                                        $currentData['var'] = 'var';
                                        break;

                                    case T_VARIABLE:
                                        #
                                        # Global variable
                                        #
                                        $value = '';
                                        if (get_class($ce) === 'rootDoc') {
                                            $global = &new fieldDoc($tokens[$key][1], $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                            if (isset($currentData['docComment'])) {
                                                $global->set('docComment', $currentData['docComment']);
                                            } else {
                                                unset ($global);
                                                break;
                                            }

                                            if (isset($tokens[$key - 1][0]) && isset($tokens[$key - 2][0]) && $tokens[$key - 2][0] === T_STRING && $tokens[$key - 1][0] === T_WHITESPACE) {
                                                $global->set('type', new type($tokens[$key - 2][1], $rootDoc));
                                            } else {
                                                unset ($global);
                                                break;
                                            }
                                            while (isset($tokens[$key]) && $tokens[$key] !=='=' && $tokens[$key] !==';') {
                                                $key++;
                                            }
                                            if (isset($tokens[$key]) && $tokens[$key] === '=') {
                                                $key2  = $key + 1;
                                                do {
                                                    if (is_array($tokens[$key2]))
                                                        if ($tokens[$key2][1] !=='=') $value .= $tokens[$key2][1];
                                                    elseif ($tokens[$key2]    !=='=') $value .= $tokens[$key2];
                                                    $key2++;
                                                } while (isset($tokens[$key2]) && $tokens[$key2] !==';' && $tokens[$key2] !==',' && $tokens[$key2] !==')');

                                                $value = trim($value);
                                                if (!empty($value)) {
                                                    $global->set('value', $value);
                                                    $global->set('type', new type($this->getType($value), $rootDoc));
                                                }
                                            }
                                            $global->set('data', $currentData);
                                            if (isset($currentData['package']))
                                                 $global->set('package', $currentData['package']);
                                            else $global->set('package', $currentPackage);
                                            $global->mergeData();

                                            $this->verbose('Found global variable: '.$global->name.' in package '.$const->package);

                                            $parentPackage = &$rootDoc->packageNamed($global->package, TRUE);
                                            $parentPackage->addGlobal($global);
                                            $currentData = [];
                                            unset($global);
                                            unset($msg);

                                        } elseif (
                                            #
                                            # Read member variable
                                            #
                                            (isset($currentData['var'])    &&  $currentData['var']    === 'var')      ||
                                            (isset($currentData['access']) && ($currentData['access'] === 'public'    ||
                                                                               $currentData['access'] === 'protected' ||
                                                                               $currentData['access'] === 'private'))
                                        ) {
                                            unset($name);

                                            do {
                                                $key++;
                                                if ($tokens[$key] === '=') {
                                                    #
                                                    # Start value
                                                    #
                                                    $name  = $this->previous($tokens, $key, T_VARIABLE);
                                                    $bracketCount = 0;
                                                } elseif (isset($value) && ($tokens[$key] !==',' || $bracketCount > 0) && $tokens[$key] !==';') {
                                                    #
                                                    # Set value
                                                    #
                                                    if     (($tokens[$key] === '(') || ($tokens[$key] === '[')) $bracketCount++;
                                                    elseif (($tokens[$key] === ')') || ($tokens[$key] === ']')) $bracketCount--;

                                                    if (is_array($tokens[$key]))
                                                         $value .= $tokens[$key][1];
                                                    else $value .= $tokens[$key];

                                                } elseif ($tokens[$key] === ',' || $tokens[$key] === ';') {
                                                    if (!isset($name)) $name = $this->previous($tokens, $key, T_VARIABLE);
                                                    $field = &new fieldDoc($name, $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());
                                                    unset($name);

                                                    if ($this->hasPrivateName($field->name)) $field->access = 'private';
                                                    if (!empty($value)) {
                                                        $field->set('value', trim($value));
                                                    }

                                                    if (isset($currentData['docComment'])) $field->set('docComment', $currentData['docComment']);
                                                    $field->set('data',    $currentData);
                                                    $field->set('package', $ce->package);

                                                    $this->verbose("\t".'field: '.$field->name.' is a member variable of class '.$ce->name);

                                                    $field->mergeData();
                                                    $ce->fields[$field->name] = &$field;
                                                    unset($field);
                                                }
                                            } while (isset($tokens[$key]) && $tokens[$key] !==';');
                                            $currentData = [];
                                            unset($value);
                                        }
                                        break;

                                }
                            } else {
                                #
                                # Primitive tokens
                                #
                                switch ($token) {

                                    case '{':
                                        if (!$in_parsed_string) $ce->inBody++;
                                        break;

                                    case '}':
                                        if (!$in_parsed_string) {
                                            #
                                            # End of var curly brace syntax
                                            #
                                            if ($open_curly_braces) $open_curly_braces = FALSE;
                                            else {
                                                $ce->inBody--;
                                                if ($ce->inBody === 0 && count($currentElement) > 0) {
                                                    $ce->mergeData();
                                                    array_pop($currentElement);
                                                    if (count($currentElement) > 0) {
                                                        $ce = &$currentElement[count($currentElement) - 1];
                                                    } else {
                                                        unset($ce);
                                                        $ce = &$rootDoc;
                                                    }
                                                    $currentPackage = $defaultPackage;
                                                }
                                            }
                                        }
                                        break;

                                    case ';':
                                        #
                                        # Case for closing abstract functions
                                        #
                                        if (!$in_parsed_string && $ce->inBody === 0 && count($currentElement) > 0) {
                                            $ce->mergeData();
                                            array_pop($currentElement); # Re-assign current element
                                            if (count($currentElement) > 0) {
                                                $ce = &$currentElement[count($currentElement) - 1];
                                            } else {
                                                unset($ce);
                                                $ce = &$rootDoc;
                                            }
                                        }
                                        break;

                                    case '"':
                                        #
                                        # Catch parsed strings so as to ignore tokens within
                                        #
                                        $in_parsed_string = !$in_parsed_string;
                                        break;
                                }
                            }
                        }
                    } else {
                        $this->error('Cannot read file "'.$filename.'"');
                        exit;
                    }
                }
            }
        }
        #
        # Add parent data to child elements
        #
        $this->mergeSuperClassData($rootDoc);
        return $rootDoc;
    }

    /**
     * Process a doc comment into a doc tag array.
     *
     * @param  string $comment Comment to process
     * @param  array  $rootDoc Reference the root of the parsed tokens
     * @return array           Array of doc comment data
     */
    public function parseDocComment($comment, &$root) {
        $data = [
            'docComment' => $comment,
            'tags' => []
        ];
        $explodedComment = preg_split('/\n[ \n\t\/]*\*+[ \t]*@/', LF.$comment);
        #
        # We need the leading whitespace to detect multi-line list entries
        #
        preg_match_all('/^[ \t]*[\/*]*\**( ?.*)[ \t\/*]*$/m', array_shift($explodedComment), $matches);
        if (isset($matches[1])) {
            $text = implode(LF, $matches[1]).LF;
            $data['tags']['@text'] = $this->createTag('@text', trim($text, " \n\t\0\x0B*/"), $data, $root);
        }
        #
        # Process tags
        #
        foreach ($explodedComment as $tag) {
            #
            # Strip whitespace, newlines and asterisks
            # Fixed: empty comment lines at end of docblock
            #
            $tag   = preg_replace('/(^[\s\n\*]+|[\s\*]*\*\/$)/m', ' ', $tag);
            $tag   = trim($tag);
            $parts = preg_split('/ +/', $tag);
            $name  = isset($parts[0]) ? array_shift($parts) : $tag;
            $text  = join(' ', $parts);
            if ($name) {
                switch ($name) {

                    case 'package':
                    case 'namespace':
                        $data['package'] = $text;
                        break;

                    case 'var':
                        $parts = preg_split('/\s+/', $text);
                        $data['type'] = isset($parts[0]) ? array_shift($parts) : $text;
                        if (empty($data['tags']['@text']->text)) {
                            $text = join(' ', $parts);
                            if (!empty($text)) {
                                $data['tags']['@text']->text = $text;
                            }
                        }
                        break;

                    case 'access':
                        $data['access'] = $text;
                        break;

                    case 'final':
                        $data['final'] = TRUE;
                        break;

                    case 'abstract':
                        $data['abstract'] = TRUE;
                        break;

                    case 'static':
                        $data['static'] = TRUE;
                        break;

                    default:
                        #
                        # Create tag
                        #
                        $name = '@'.$name;
                        if (isset($data['tags'][$name])) {
                            if (is_array($data['tags'][$name]))
                                 $data['tags'][$name][] = $this->createTag($name, $text, $data, $root);
                            else $data['tags'][$name]   = [$data['tags'][$name], $this->createTag($name, $text, $data, $root)];

                        } else   $data['tags'][$name] = &$this->createTag($name, $text, $data, $root);
                }
            }
        }
        return $data;
    }

    /**
     * Gets previous token of a certain type from token array.
     *
     * @param  array   &$tokens   Reference the array of tokens to search
     * @param  integer $key       Key to start searching from
     * @param  integer $whatToGet Type of token to look for
     * @return array|boolean      Value of found token or FALSE
     */
    private function previous(&$tokens, $key, $whatToGet) {
        $key--;
        if (!is_array($whatToGet)) $whatToGet = [$whatToGet];
        while (!is_array($tokens[$key]) || !in_array($tokens[$key][0], $whatToGet)) {
            $key--;
            if (!isset($tokens[$key])) return FALSE;
        }
        return $tokens[$key][1];
    }

    /**
     * Returns the path of sources.
     *
     * @return string Path to processing source file
     */
    public function sourcePath() {
        return $this->source[$this->sourceIndex];
    }

    /**
     * Writes a verbose message to standard output.
     *
     * @param string $msg Verbose message to output
     */
    public function verbose($msg) {
        if ($this->options['verbose']) echo $msg, LF;
    }

    /**
     * Writes an error message to standard error.
     *
     * @param string $msg Error message to output
     */
    public function error($msg) {
        fwrite(STDERR, 'ERROR: '.$msg.LF);
    }

    /**
     * Writes a warning message to standard error.
     *
     * @param string $msg Warning message to output
     */
    public function warning($msg) {
        fwrite(STDERR, 'WARNING: '.$msg.LF);
    }
}
