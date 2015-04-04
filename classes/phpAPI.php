<?php
/** php tokenizer and parser.
 * Particularly the packages, classes and options specified by the user.
 * It is the root of the parsed tokens and is passed to the doclet to be formatted into output.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/phpapi.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */
class phpapi {

    /** Specifies the name of the class that starts the doclet used in generating the documentation.
     * @var string
     */
    public $doclet = 'htmlFrames';

    /** Array of files to parse.
     * @var array
     */
    public $files = [];

    /** Options from config file.
     * @var array
     */
    public $options = [];

    /** The path phpapi is running from.
     * @var string
     */
    public $path = '.';

    /** Number of parsed source files
     * @var integer
     */
    public $sourceIndex = 0;

    /** Directory containing files for parsing.
     * @var string
     */
    public $sourcePath = './';

    /** The time in microseconds at the start of execution.
     * @var integer
     */
    private $startTime = 0;

    /** Constructor.
     *
     *  @param string $config The configuration file to use for this run of phpapi (Default = 'default.ini')
     */
    public function phpapi($config = 'default.ini') {
        # Record start time
        $this->startTime = $this->getTime();

        # Set the path
        $this->path = dirname(dirname(__FILE__));

        # Read config file
        if (is_file($config)) {
            $this->options = parse_ini_file($config);
            if (count($this->options) == 0) {
                $this->error('Cannot parse configuration file "'.$config.'"');
                exit;
            }
        } else {
            $this->error('Cannot find configuration file "'.$config.'"');
            exit;
        }

        # Set phpapi options
        if (isset($this->options['source'])) {
            $this->sourcePath = [];
            foreach (explode(',', $this->options['source']) as $path) {
                $this->sourcePath[] = $this->fixPath($path, getcwd());
            }
        }

        if (isset($this->options['files']))
             $files = explode(',', $this->options['files']);
        else $files = '*.php';

        if (isset($this->options['ignore']))    $this->options['ignore']   = explode(',', $this->options['ignore']);
        if (isset($this->options['overview']))  $this->options['overview'] = $this->makeAbsolutePath($this->options['overview'], $this->sourcePath[0]);
        if (isset($this->options['doclet']))    $this->doclet    = $this->options['doclet'];

        $this->verbose('Searching for files to parse...');

        $this->files = [];
        foreach ($this->sourcePath as $path) {
            $this->files[$path] = array_unique($this->buildFileList($files, $path));
        }
        if (count($this->files) == 0) {
            $this->error('Cannot find any files to parse');
            exit;
        }
    }

    /** Builds a complete list of file to parse.
     * Expands out wildcards and traverse directories if asked to.
     *
     * @param  array  $files Array of filenames to expand
     * @param  string $dir   Directory to scan
     * @return array         List of files
     */
    private function buildFileList($files, $dir) {
        $list = [];
        $dir  = realpath($dir);
        if (!$dir) {
            return $list;
        }

        $dir = $this->fixPath($dir);
        foreach ($files as $filename) {
            $filename = $this->makeAbsolutePath(trim($filename), $dir);
            $globResults = glob($filename); # Switch slashes since old versions of glob need forward slashes
            if ($globResults) {
                foreach ($globResults as $filepath) {
                    $okay = TRUE;
                    foreach ($this->options['ignore'] as $ignore) {
                        if (strstr($filepath, trim($ignore))) $okay = FALSE;
                    }
                    if ($okay) $list[] = realpath($filepath);
                }
            } elseif (!$this->options['subDirs']) $this->error('Cannot find file "'.$filename.'"');
        }

        # Recurse into subdir
        if ($this->options['subDirs']) {
            $globResults = glob($dir.'*', GLOB_ONLYDIR);             # Get subDirs
            if ($globResults) {
                foreach ($globResults as $dirName) {
                    $okay = TRUE;
                    foreach ($this->options['ignore'] as $ignore) {
                        if (strstr($dirName, trim($ignore))) $okay = FALSE;
                    }
                    if ($okay && (GLOB_ONLYDIR || is_dir($dirName))) { # Handle missing only dir support
                        $list = array_merge($list, $this->buildFileList($files, $this->makeAbsolutePath($dirName, $this->path)));
                    }
                }
            }
        }
        return $list;
    }

    /** Writes a message to standard output.
     *
     * @param string $msg Verbose message to output
     * @return void
     */
    public function verbose($msg) {
        if ($this->options['verbose']) echo $msg, LF;
    }

    /** Writes a warning message to standard error.
     *
     * @param string $msg Warning message to output
     * @return void
     */
    public function warning($msg) {
        fwrite(STDERR, 'WARNING: '.$msg.LF);
    }

    /** Writes an error message to standard error.
     *
     * @param string $msg Error message to output
     * @return void
     */
    public function error($msg) {
        fwrite(STDERR, 'ERROR: '.$msg.LF);
    }

    /** Gets the current time in microseconds.
     *
     * @return integer Current time
     */
    private function getTime() {
        $microtime = explode(' ', microtime());
        return $microtime[0] + $microtime[1];
    }

    /** Turns path into an absolute path using the given prefix?
     *
     * @param  string $path   Path to make absolute
     * @param  string $prefix Absolute path to append to relative path
     * @return string         Absolute path to needed object
     */
    public function makeAbsolutePath($path, $prefix) {
        if (
            substr($path, 0, 1) == '/'    ||  # Unix root
            substr($path, 1, 2) == ':\\'  ||  # Windows root
            substr($path, 0, 2) == '~/'   ||  # Unix home directory
            substr($path, 0, 2) == '\\\\' ||  # Windows network location
            preg_match('|^[a-z]+://|', $path) # Url
        ) {
            return $path;

        } else {
            if (substr($path, 0, 2) == './') $path = substr($path, 2);

            $absPath = $this->fixPath($prefix).$path;
            $count = 1;
            while ($count > 0) {
                $absPath = preg_replace('|\w+/\.\./|', '', $absPath, -1, $count);
            }
            return $absPath;
        }
    }

    /** Adds a trailing slash to a path if it does not have one.
     *
     * @param string $path Path to postfix
     * @return string      Fixed path
     */
    public function fixPath($path) {
        return (substr($path, -1, 1) != '/' && substr($path, -1, 1) != '\\') ? $path.'/' : $path;
    }

    /** Returns the path phpapi is running from.
     *
     * @return string Path to doclet
     */
    public function docletPath() {
        return realpath($this->fixPath(DOCLETS).$this->fixPath($this->doclet)).DS;
    }

    /** Returns the source path.
     *
     * @return string Path to processing source file
     */
    public function sourcePath() {
        return realpath($this->sourcePath[$this->sourceIndex]);
    }

    /** Returns a reference to the set options.
     *
     * @return array An array of options
     */
    function &options() {
        return $this->options;
    }

    /** Parses files into tokens and create rootDoc.
     *
     * @return array $rootDoc The root of the parsed tokens
     */
    function &parse() {
        $rootDoc = &new rootDoc($this);
        $ii = 0;
        foreach ($this->files as $path => $files) {
            $this->sourceIndex = $ii++;
            foreach ($files as $filename) {
                if ($filename) {
                    $this->verbose('File "'.$filename.'"');
                    $fileString = file_get_contents($filename);
                    if ($fileString != FALSE) {
                        $fileString  = str_replace(["\r\n", "\n\r", "\r", "\n"], LF, $fileString);

                        # Remove the empty lines from a file.
//                        $fileString  = str_replace(["\n\n"], LF, $fileString);
                        $tokens = token_get_all($fileString);

                        # This array holds data gathered before the type of element is discovered and an object is created for it, including doc comment data.
                        # This data is stored in the object once it has been created and then merged into the objects data fields upon object completion.
                        $currentData    = [];
                        $currentPackage = $this->options['defaultPackage'];
                        $defaultPackage = $oldDefaultPackage = $currentPackage;
                        $fileData       = [];

                        $currentElement = [];     # Stack of element family, current at top of stack
                        $ce = &$rootDoc;          # Reference to element at top of stack

                        $open_curly_braces = FALSE;
                        $in_parsed_string  = FALSE;

                        $lineNumber    = 1;
                        $commentNumber = 0;
                        $numOfTokens   = count($tokens);

                        for ($key = 0; $key < $numOfTokens; $key++) {
                            $token = $tokens[$key];
                            if (!$in_parsed_string && is_array($token)) {
                                $lineNumber += substr_count($token[1], LF);
                                if ($commentNumber == 1 && (
                                    $token[0] == T_CLASS     ||
                                    $token[0] == T_INTERFACE ||
                                    $token[0] == T_FUNCTION  ||
                                    $token[0] == T_VARIABLE
                                   )) { # We have a code block after the 1st comment, so it is not a file level comment
                                    $fileData = [];
                                }
                                switch ($token[0]) {

                                    case T_DOC_COMMENT:
                                        $currentData = array_merge($currentData, $this->processDocComment($token[1], $rootDoc));
                                        if ($currentData) {
                                            $commentNumber++;
                                            if ($commentNumber == 1) {
                                                if (isset($currentData['package'])) { # Store 1st comment incase it is a file level comment
                                                    $oldDefaultPackage = $defaultPackage;
                                                    $defaultPackage    = $currentData['package'];
                                                }
                                                $fileData = $currentData;
                                            }
                                        }
                                        break;

                                    case T_CLASS:

                                        $class = &new classDoc($this->getProgramElementName($tokens, $key), $rootDoc, $filename, $lineNumber, $this->sourcePath()); # Create class object

                                        $this->verbose('Found class: '.$class->name());

                                        if (isset($currentData['docComment'])) {
                                            $class->set('docComment', $currentData['docComment']);
                                        }
                                        $class->set('data', $currentData);
                                        if (!empty($currentData['package'])) {
                                            $currentPackage = $currentData['package'];
                                        }
                                        $class->set('package', $currentPackage);
                                        $parentPackage = &$rootDoc->packageNamed($class->packageName(), TRUE);
                                        $parentPackage->addClass($class);

                                        # Set parent reference
                                        $class->setByRef('parent', $parentPackage);
                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$class;
                                        $ce = &$class;
                                        break;

                                    case T_INTERFACE:

                                        $interface = &new classDoc($this->getProgramElementName($tokens, $key), $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                        $this->verbose('Found interface: '.$interface->name());

                                        if (isset($currentData['docComment'])) {
                                            $interface->set('docComment', $currentData['docComment']);
                                        }
                                        $interface->set('data', $currentData);
                                        $interface->set('interface', TRUE);
                                        if (!empty($currentData['package'])) {
                                            $currentPackage = $currentData['package'];
                                        }
                                        $interface->set('package', $currentPackage);
                                        $parentPackage = &$rootDoc->packageNamed($interface->packageName(), TRUE);
                                        $parentPackage->addClass($interface);

                                        # Set parent reference
                                        $interface->setByRef('parent', $parentPackage);
                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$interface;
                                        $ce = &$interface;
                                        break;

                                    case T_TRAIT:

                                        $trait = &new classDoc($this->getProgramElementName($tokens, $key), $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                        $this->verbose('Found trait: '.$trait->name());

                                        if (isset($currentData['docComment'])) {
                                            $trait->set('docComment', $currentData['docComment']);
                                        }
                                        $trait->set('data', $currentData);
                                        $trait->set('trait', TRUE);
                                        if (!empty($currentData['package'])) {
                                            $currentPackage = $currentData['package'];
                                        }
                                        $trait->set('package', $currentPackage);
                                        $parentPackage = &$rootDoc->packageNamed($trait->packageName(), TRUE);
                                        $parentPackage->addClass($trait);

                                        # Set parent reference
                                        $trait->setByRef('parent', $parentPackage);
                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$trait;
                                        $ce = &$trait;
                                        break;

                                    case T_EXTENDS:

                                        $superClassName = $this->getProgramElementName($tokens, $key);
                                        $ce->set('superclass', $superClassName);
                                        if ($superClass = &$rootDoc->classNamed($superClassName) && $commentTag = &$superClass->tags('@text')) {
                                            $ce->setTag('@text', $commentTag);
                                        }
                                        break;

                                    case T_IMPLEMENTS:

                                        $interfaceName = $this->getProgramElementName($tokens, $key);
                                        $interface     = &$rootDoc->classNamed($interfaceName);
                                        if ($interface) $ce->set('interfaces', $interface);
                                        break;

                                    case T_THROW:

                                        $className = $this->getNext($tokens, $key, T_STRING);
                                        $class = &$rootDoc->classNamed($className);
                                        if ($class)
                                             $ce->setByRef('throws', $class);
                                        else $ce->set('throws', $className);
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

                                    case T_ABSTRACT:
                                        $currentData['abstract'] = TRUE;
                                        break;

                                    case T_FINAL:
                                        $currentData['final'] = TRUE;
                                        break;

                                    case T_STATIC:
                                        $currentData['static'] = TRUE;
                                        break;

                                    case T_VAR:
                                        $currentData['var'] = 'var';
                                        break;

                                    case T_CONST:
                                        $currentData['var'] = 'const';
                                        break;

                                    case T_USE:
                                        if (get_class($ce) == 'classDoc') {
                                            while ($tokens[++$key][0] != ';') {
                                                if ($tokens[$key][0] == T_STRING) {
                                                    $className = $tokens[$key][1];
                                                    $class = &$rootDoc->classNamed($className);
                                                    if ($class)
                                                         $ce->setByRef('traits', $class);
                                                    else $ce->set('traits', $className);
                                                }
                                            }
                                        }
                                        break;

                                    case T_NAMESPACE:
                                    case T_NS_C:
                                        $namespace = '';
                                        while ($tokens[++$key][0] != T_STRING);
                                        $namespace = $tokens[$key++][1];
                                        while ($tokens[$key][0] == T_NS_SEPARATOR) {
                                            $namespace .= $tokens[$key++][1].$tokens[$key++][1];
                                        }
                                        $currentPackage = $defaultPackage = $oldDefaultPackage = $namespace;
                                        $key--;
                                        break;

                                    case T_FUNCTION:

                                        $name   = $this->getProgramElementName($tokens, $key);
                                        $method = &new methodDoc($name, $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                        $msg = "\t".'method : '.$method->name();

                                        if (isset($currentData['docComment'])) $method->set('docComment', $currentData['docComment']);
                                        $method->set('data', $currentData);
                                        $ceClass = get_class($ce);
                                        if ($ceClass == 'rootDoc') {

                                            $msg  = 'Found function: '.$method->name();
                                            $msg .= ' - global';

                                            if (isset($currentData['access']) && $currentData['access'] == 'private')
                                                 $method->makePrivate();
                                            if (!empty($currentData['package'])) {
                                                $method->set('package', $currentData['package']);
                                                $msg .= '- datapackage "'. $currentData['package'].'"';
                                            } else {
                                                $method->set('package', $currentPackage);
                                                $msg .= '- currentpackage "'. $currentPackage.'"';
                                            }
                                            $method->mergeData();
                                            $parentPackage = &$rootDoc->packageNamed($method->packageName(), TRUE);
                                            $parentPackage->addFunction($method);

                                        } elseif ($ceClass == 'classDoc' || $ceClass == 'methodDoc') {
                                            $method->set('package', $ce->packageName()); # Set package
                                            if ($method->name() == '__construct' || $method->name() == $ce->name()) {

                                                $msg .= ' is a constructor of '.get_class($ce).' '.$ce->name();

                                                $method->set('name', $method->name());
                                                $ce->addMethod($method);
                                            } else {
                                                if ($this->hasPrivateName($method->name()))
                                                    $method->makePrivate();
                                                if (isset($currentData['access']) && $currentData['access'] == 'private')
                                                    $method->makePrivate();

                                                $msg .= ' is a method of class '.$ce->name();

                                                $method->mergeData();
                                                $ce->addMethod($method);
                                            }
                                        }

                                        $this->verbose($msg).LF;

                                        $currentData = [];
                                        $currentElement[count($currentElement)] = &$method;
                                        $ce = &$method;
                                        unset($name);
                                        unset($msg);
                                        break;

                                    case T_STRING:

                                        if ($token[1] == 'define') {
                                            $const = &new fieldDoc($this->getNext($tokens, $key, T_CONSTANT_ENCAPSED_STRING), $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                            $msg = 'Found global constant '.$const->name();

                                            $const->set('final', TRUE);
                                            $value = '';
                                            do {
                                                $key++;
                                            } while (isset($tokens[$key]) && $tokens[$key] != ',');
                                            $key++;

                                            while (isset($tokens[$key]) && $tokens[$key] != ')') {
                                                if (is_array($tokens[$key]))
                                                     $value .= $tokens[$key][1];
                                                else $value .= $tokens[$key];
                                                $key++;
                                            }

                                            $const->set('value', trim($value));
                                            $const->set('type', new type($this->getType(trim($value)), $rootDoc));
                                            unset($value);

                                            if (isset($currentData['docComment'])) $const->set('docComment', $currentData['docComment']);

                                            $const->set('data', $currentData);

                                            if (isset($currentData['package']))
                                                 $const->set('package', $currentData['package']);
                                            else $const->set('package', $currentPackage);

                                            $this->verbose($msg.' in package '.$const->packageName());

                                            $const->mergeData();
                                            $parentPackage = &$rootDoc->packageNamed($const->packageName(), TRUE);
                                            $parentPackage->addGlobal($const);
//                                            $currentData   = [];

                                        } elseif (isset($currentData['var']) && $currentData['var'] == 'const') {

                                            # Member constant
                                            do {
                                                $key++;
                                                if ($tokens[$key] == '=') {
                                                    $name  = $this->getPrev($tokens, $key, [T_VARIABLE, T_STRING]);
                                                    $value = '';
                                                } elseif (isset($value) && $tokens[$key] != ',' && $tokens[$key] != ';') {

                                                    # Set value
                                                    if (is_array($tokens[$key]))
                                                         $value .= $tokens[$key][1];
                                                    else $value .= $tokens[$key];
                                                } elseif ($tokens[$key] == ',' || $tokens[$key] == ';') {
                                                    if (!isset($name)) $name = $this->getPrev($tokens, $key, [T_VARIABLE, T_STRING]);
                                                    $const = &new fieldDoc($name, $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                                    $msg = 'Found const: '.$const->name();

                                                    if ($this->hasPrivateName($const->name())) $const->makePrivate();
                                                    $const->set('final', TRUE);
                                                    if (isset($value)) {
                                                        $value = trim($value);
                                                        $const->set('value', $value);
                                                        $const->set('type', new type($this->getType($value), $rootDoc));
                                                    }
                                                    if (isset($currentData['docComment'])) $const->set('docComment', $currentData['docComment']);
                                                    $const->set('data',    $currentData);
                                                    $const->set('package', $ce->packageName());
                                                    $const->set('static',  TRUE);

                                                    $this->verbose($msg.' is a member constant of '.get_class($ce).' '.$ce->name());

                                                    $const->mergeData();
                                                    $ce->addConstant($const);
                                                    unset($name);
                                                    unset($value);
                                                }
                                            } while (isset($tokens[$key]) && $tokens[$key] != ';');
                                            $currentData = [];

                                        } elseif (get_class($ce) == 'methodDoc' && $ce->inBody == 0) {

                                            # Function parameter
                                            $typehint = NULL;
                                            do {
                                                $key++;
                                                if (!isset($tokens[$key]))
                                                    break;

                                                if ($tokens[$key] == ',' || $tokens[$key] == ')') {
                                                    unset($param);

                                                } elseif (is_array($tokens[$key])) {
                                                    if ($tokens[$key][0] == T_STRING && !isset($param)) {
                                                        $typehint = $tokens[$key][1];
                                                    } elseif ($tokens[$key][0] == T_VARIABLE && !isset($param)) {
                                                        $param = &new fieldDoc($tokens[$key][1], $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                                        $msg = "\t".'parameter: $'.$param->name();

                                                        if (isset($currentData['docComment'])) {
                                                            $param->set('docComment', $currentData['docComment']);
                                                        }
                                                        if ($typehint) {
                                                            $param->set('type', new type($typehint, $rootDoc));
                                                            $msg .= ' has a typehint of '.$typehint;
                                                        }
                                                        $param->set('data', $currentData);
                                                        $param->set('package', $ce->packageName());

                                                        $this->verbose($msg.' is a parameter of '.$ce->name());

                                                        $param->mergeData();
                                                        $ce->addParameter($param);
                                                        $typehint = NULL;

                                                    } elseif (isset($param) && ($tokens[$key][0] == T_STRING || $tokens[$key][0] == T_CONSTANT_ENCAPSED_STRING || $tokens[$key][0] == T_LNUMBER)) { # Set value
                                                        $value = $tokens[$key][1];
                                                        $param->set('value', $value);
                                                        if (!$typehint) {
                                                            if (is_numeric($value)) {
                                                                $param->set('type', new type('integer', $rootDoc));
                                                            } elseif (strtolower($value) == 'true' || strtolower($value) == 'false') {
                                                                $param->set('type', new type('boolean', $rootDoc));
                                                            } elseif (
                                                                substr($value, 0, 1) == '"' && substr($value, -1, 1) == '"' ||
                                                                substr($value, 0, 1) == "'" && substr($value, -1, 1) == "'"
                                                            ) {
                                                                $param->set('type', new type('string', $rootDoc));
                                                            }
                                                        }
                                                    }
                                                }
                                            } while (isset($tokens[$key]) && $tokens[$key] != ')');

                                            $currentData = [];
                                        }
                                        break;

                                    case T_VARIABLE:

                                        # Global variable
                                        if (get_class($ce) == 'rootDoc') {

                                            $global = &new fieldDoc($tokens[$key][1], $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                            if (isset($currentData['docComment'])) {
                                                $global->set('docComment', $currentData['docComment']);
                                            } else {
                                                unset ($global);
                                                break;
                                            }
                                            $msg = 'Found global variable: '.$global->name();

                                            if (isset($tokens[$key - 1][0]) && isset($tokens[$key - 2][0]) && $tokens[$key - 2][0] == T_STRING && $tokens[$key - 1][0] == T_WHITESPACE) {
                                                $global->set('type', new type($tokens[$key - 2][1], $rootDoc));
                                            } else {
                                                unset ($global);
                                                break;
                                            }
                                            while (isset($tokens[$key]) && $tokens[$key] != '=' && $tokens[$key] != ';') {
                                                $key++;
                                            }
                                            if (isset($tokens[$key]) && $tokens[$key] == '=') {
                                                $default = '';
                                                $key2 = $key + 1;
                                                do {
                                                    if (is_array($tokens[$key2]))
                                                        if ($tokens[$key2][1] != '=') $default .= $tokens[$key2][1];
                                                    elseif ($tokens[$key2]    != '=') $default .= $tokens[$key2];
                                                    $key2++;
                                                } while (isset($tokens[$key2]) && $tokens[$key2] != ';' && $tokens[$key2] != ',' && $tokens[$key2] != ')');

                                                $global->set('value', trim($default));
                                            }
                                            $global->set('data', $currentData);
                                            if (isset($currentData['package']))
                                                 $global->set('package', $currentData['package']);
                                            else $global->set('package', $currentPackage);
                                            $global->mergeData();

                                            $this->verbose($msg.' in package '.$const->packageName());

                                            $parentPackage = &$rootDoc->packageNamed($global->packageName(), TRUE);
                                            $parentPackage->addGlobal($global);
                                            $currentData = [];

                                        } elseif (
                                            # Read member variable
                                            (isset($currentData['var'])    &&  $currentData['var']    == 'var')      ||
                                            (isset($currentData['access']) && ($currentData['access'] == 'public'    ||
                                                                               $currentData['access'] == 'protected' ||
                                                                               $currentData['access'] == 'private'))
                                        ) {
                                            unset($name);

                                            do {
                                                $key++;
                                                if ($tokens[$key] == '=') {

                                                    # Start value
                                                    $name  = $this->getPrev($tokens, $key, T_VARIABLE);
                                                    $value = '';
                                                    $bracketCount = 0;
                                                } elseif (isset($value) && ($tokens[$key] != ',' || $bracketCount > 0) && $tokens[$key] != ';') {

                                                    # Set value
                                                    if     (($tokens[$key] == '(') || ($tokens[$key] == '[')) $bracketCount++;
                                                    elseif (($tokens[$key] == ')') || ($tokens[$key] == ']')) $bracketCount--;

                                                    if (is_array($tokens[$key]))
                                                         $value .= $tokens[$key][1];
                                                    else $value .= $tokens[$key];
                                                } elseif ($tokens[$key] == ',' || $tokens[$key] == ';') {
                                                    if (!isset($name)) $name = $this->getPrev($tokens, $key, T_VARIABLE);
                                                    $field = &new fieldDoc($name, $ce, $rootDoc, $filename, $lineNumber, $this->sourcePath());

                                                    $msg = "\t".'field: '.$field->name();

                                                    if ($this->hasPrivateName($field->name())) $field->makePrivate();
                                                    if (isset($value)) {
                                                        $value = trim($value);
                                                        $field->set('value', $value);
                                                    }
                                                    if (isset($currentData['docComment'])) $field->set('docComment', $currentData['docComment']);
                                                    $field->set('data',    $currentData);
                                                    $field->set('package', $ce->packageName());

                                                    $this->verbose($msg.' is a member variable of class '.$ce->name());

                                                    $field->mergeData();
                                                    $ce->addField($field);
                                                    unset($name);
                                                    unset($value);
                                                }
                                            } while (isset($tokens[$key]) && $tokens[$key] != ';');
                                            $currentData = [];
                                        }
                                        break;

                                    case T_CURLY_OPEN:
                                    case T_DOLLAR_OPEN_CURLY_BRACES: # We must catch this so we don't accidently step out of the current block
                                        $open_curly_braces = TRUE;
                                        break;
                                }
                            } else {

                                # Primitive tokens
                                switch ($token) {

                                    case '{':
                                        if (!$in_parsed_string) $ce->inBody++;
                                        break;

                                    case '}':
                                        if (!$in_parsed_string) {

                                            # End of var curly brace syntax
                                            if ($open_curly_braces) $open_curly_braces = FALSE;
                                            else {
                                                $ce->inBody--;
                                                if ($ce->inBody == 0 && count($currentElement) > 0) {
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

                                        # Case for closing abstract functions
                                        if (!$in_parsed_string && $ce->inBody == 0 && count($currentElement) > 0) {
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

                                        # Catch parsed strings so as to ignore tokens within
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
        # Add parent data to child elements
        $this->mergeSuperClassData($rootDoc);
        return $rootDoc;
    }

    private function getType ($var) {
        if ((substr($var, 0, 5) == 'array') || ((substr($var, 0, 1) == '[') && (substr($var, -1, 1)== ']'))) {
            return 'array';
        }
        if (is_numeric($var)) {
            return 'integer';
        } elseif ((strtolower($var) == 'true') || (strtolower($var) == 'false')) {
            return 'boolean';
        } elseif (is_string($var)) {
            return 'string';
        }
        return 'mixed';
    }

    /** Loads and runs the doclet.
     *
     * @param  array $rootDoc The root of the parsed tokens
     * @return void
     */
    public function execute(&$rootDoc) {
        $docletFile = $this->fixPath(DOCLETS).$this->doclet.DS.$this->doclet.'.php';
        if (is_file($docletFile)) { # Load doclet

            $this->verbose('Loading doclet "'.$this->doclet.'"');

            require_once($docletFile);
            $doclet = &new $this->doclet($rootDoc, $this->getFormatter());
        } else {
            $this->error('Cannot find doclet "'.$docletFile.'"');
        }
        $this->verbose('Done ('.round($this->getTime() - $this->startTime, 2).' seconds)');
    }

    /** Creates the formatter and returns it.
     *
     * @return object TextFormatter
     */
    public function getFormatter() {
        $formatterFile = $this->fixPath(FORMATTERS).$this->options['formatter'].'.php';
        if (is_file($formatterFile)) {
            require_once($formatterFile);
            return new $this->options['formatter']();

        } else {
            $this->error('Cannot find formatter "'.$formatterFile.'"');
            exit;
        }
    }

    /** Merges data of the superclass.
     *
     * @param array  $rootDoc The root of the parsed tokens
     * @param string $parent  Superclass (Default = NULL)
     */
    public function mergeSuperClassData(&$rootDoc, $parent = NULL) {
        $classes = &$rootDoc->classes();
        foreach ($classes as $name => $class) {
            if ($classes[$name]->superclass() == $parent) {
                $classes[$name]->mergeSuperClassData();
                $this->mergeSuperClassData($rootDoc, $classes[$name]->name());
            }
        }
    }

    /** Recursively merge two arrays into a single array.
     * This differs from the PHP function array_merge_recursive as it replaces values
     * with the same index from the first array with items from the second.
     *
     * @param  mixed[] $one Array one
     * @param  mixed[] $two Array two
     * @return mixed[]      Merged array
     */
    public function mergeArrays($one, $two) {
        foreach ($two as $key => $item) {
            if (isset($one[$key]) && is_array($one[$key]) && is_array($item))
                 $one[$key] = $this->mergeArrays($one[$key], $item);
            else $one[$key] = $item;
        }
        return $one;
    }

    /** Gets next token of a certain type from token array.
     *
     * @param  array   $tokens    Token array to search
     * @param  integer $key       Key to start searching from
     * @param  integer $whatToGet Type of token to look for
     * @param  integer $maxDist   Optional max distance from key to look at; default is 0 for all.
     * @return string|boolean     Value of found token or FALSE
     */
    private function getNext(&$tokens, $key, $whatToGet, $maxDist = 0) {
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

    /** Gets previous token of a certain type from token array.
     *
     * @param  array   $tokens    Token array to search
     * @param  integer $key       Key to start searching from
     * @param  integer $whatToGet Type of token to look for
     * @return string|boolean     Value of found token or FALSE
     */
    private function getPrev(&$tokens, $key, $whatToGet) {
        $key--;
        if (!is_array($whatToGet)) $whatToGet = [$whatToGet];
        while (!is_array($tokens[$key]) || !in_array($tokens[$key][0], $whatToGet)) {
            $key--;
            if (!isset($tokens[$key])) return FALSE;
        }
        return $tokens[$key][1];
    }

    /** Gets program element name from the token list.
     *
     * @param  array   $tokens Token array
     * @param  integer $key    Key to start searching from
     * @return string          Name of the program element
     */
    private function getProgramElementName(&$tokens, $key) {
        $name = '';
        $key++;
        while (
            $tokens[$key] && (
                $tokens[$key] == '&' || (
                    isset($tokens[$key][0]) && isset($tokens[$key][1]) && (
                        $tokens[$key][0] == T_WHITESPACE ||
                        $tokens[$key][0] == T_STRING ||
                        $tokens[$key][0] == T_NS_SEPARATOR
                    )
                )
            )
        ) {
            if (isset($tokens[$key][1])) $name .= $tokens[$key][1];
            $key++;
        }
        return trim($name);
    }

    /** Process a doc comment into a doc tag array.
     *
     * @param  string $comment The comment to process
     * @param  array  $rootDoc Referencw to the root of the parsed tokens
     * @return array           Array of doc comment data
     */
    public function processDocComment($comment, &$root) {
        $data = [
            'docComment' => $comment,
            'tags' => []
        ];
        $explodedComment = preg_split('/\n[ \n\t\/]*\*+[ \t]*@/', LF.$comment);

         # We need the leading whitespace to detect multi-line list entries
        preg_match_all('/^[ \t]*[\/*]*\**( ?.*)[ \t\/*]*$/m', array_shift($explodedComment), $matches);
        if (isset($matches[1])) {
            $txt = implode(LF, $matches[1]).LF;
            $data['tags']['@text'] = $this->createTag('@text', trim($txt, " \n\t\0\x0B*/"), $data, $root);
        }

        # Process tags
        foreach ($explodedComment as $tag) {
            # Strip whitespace, newlines and asterisks
            # Fixed: empty comment lines at end of docblock
            $tag   = preg_replace('/(^[\s\n\*]+|[\s\*]*\*\/$)/m', ' ', $tag);
            $tag   = preg_replace('/\n+/', '', $tag);
            $tag   = trim($tag);
            $parts = preg_split('/\s+/', $tag);
            $name  = isset($parts[0]) ? array_shift($parts) : $tag;
            $text  = join(' ', $parts);
            if ($name) {
                switch ($name) {

                    case 'package':
                    case 'namespace':
                        $data['package'] = $text;
                        break;

                    case 'var':
                        $data['type'] = $text;
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

                    default:         # Create tag
                        $name = '@'.$name;
                        if (isset($data['tags'][$name])) {
                            if (is_array($data['tags'][$name]))
                                 $data['tags'][$name][] = $this->createTag($name, $text, $data, $root);
                            else $data['tags'][$name]   = [$data['tags'][$name], $this->createTag($name, $text, $data, $root)];

                        } else   $data['tags'][$name]   = &$this->createTag($name, $text, $data, $root);
                }
            }
        }
        return $data;
    }

    /** Creates a tag.
     * This method first tries to load a Taglet for the given tag name, upon failing it
     * then tries to load a phpapi specialised tag class (e.g. classes/paramtag.php),
     * if it still has not found a tag class it uses the standard tag class.
     *
     * @param  string $name    The name of the tag
     * @param  string $text    The contents of the tag
     * @param  array  $data    Reference to doc comment data array
     * @param  array  $rootDoc The root of the parsed tokens
     * @return obgect          Tag object
     */
    function &createTag($name, $text, &$data, &$root) {
        $class = substr($name, 1);
        if ($class) {
            $tagletFile = $this->makeAbsolutePath($this->fixPath(TAGLETS).substr($name, 1).'.php', $this->path);
            if (is_file($tagletFile)) {

                # Load taglet for this tag.
                if (!class_exists($class)) require_once($tagletFile);
                $tag = &new $class($text, $data, $root);
                return $tag;

            } else {
                $tagFile = $this->makeAbsolutePath(CLASSES.$class.'Tag.php', $this->path);
                if (is_file($tagFile)) {

                    # Load class for this tag.
                    $class .= 'Tag';
                    if (!class_exists($class)) require_once($tagFile);
                    $tag = &new $class($text, $data, $root);
                    return $tag;

                } else {
                    # Create standard tag.
                    $tag = &new tag($name, $text, $root);
                    return $tag;
                }
            }
        }
    }

    /** Does the given element name conform to the format that is used for private elements?
     *
     * @param  string  $name The name to check
     * @return boolean
     */
    public function hasPrivateName($name) {
        return substr($name, 0, 1) == '_';
    }
}
