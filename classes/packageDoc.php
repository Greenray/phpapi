<?php
# phpapi: The PHP Documentation Creator

/** Represents a PHP package.
 * Provides access to information about the package,
 * the package's comment and tags, and the classes in the package.
 *
 * @file      classes/packageDoc.php
 * @version   2.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class packageDoc extends doc {

    /** The classes in this package.
     * @var classDoc[]
     */
    public $_classes = [];

    /** The globals in this package.
     * @var fieldDoc[]
     */
    public $_globals = [];

    /** The functions in this package.
     * @var methodDoc[]
     */
    public $_functions = [];

    /** Constructor.
     * @param  string  $name Packqge name
     * @param  rootDoc $root Reference to rootDoc
     * @return void
     */
    public function packageDoc($name, &$root) {
        $this->_name =  $name;
        $this->_root =& $root;
    }

    /** Returns the package path.
     * @return string Path to package
     */
    public function asPath() {
        return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_name)));
    }

    /** Calculates the depth of this package from the root.
     * @return integer The value of the depth of this package from the root
     */
    public function depth() {
        $depth  = substr_count($this->_name, '.');
        $depth += substr_count($this->_name, '\\');
        $depth += substr_count($this->_name, '/');
        return $depth;
    }

    /** Adds a class to this package.
     * @param classDoc $class Reference to class
     */
    public function addClass(&$class) {
        if (isset($this->_classes[$class->name()])) {
            $phpapi =& $this->_root->phpapi();
            echo LF;
            $phpapi->warning('Found class '.$class->name().' again, overwriting previous version');
        }
        $this->_classes[$class->name()] =& $class;
    }

    /** Adds a global to this package.
     * @param fieldDoc $global Reference to global element
     */
    public function addGlobal(&$global) {
        if (!isset($this->_globals[$global->name()])) $this->_globals[$global->name()] =& $global;
    }

    /** Adds a function to this package.
     * @param methodDoc $function Reference to function
     */
    public function addFunction(&$function) {
        if (isset($this->_functions[$function->name()])) {
            $phpapi =& $this->_root->phpapi();
            echo LF;
            $phpapi->warning('Found function '.$function->name().' again, overwriting previous version');
        }
        $this->_functions[$function->name()] =& $function;
    }

    /** Gets all included classes (including exceptions and interfaces).
     * @return classDoc[] An array of classes
     */
    function &allClasses() {
        return $this->_classes;
    }

    /** Gets exceptions in this package.
     * @return classDoc[] An array of exceptions
     */
    function &exceptions() {
        $exceptions = NULL;
        foreach ($this->_classes as $name => $exception) {
            if ($exception->isException()) $exceptions[$name] =& $this->_classes[$name];
        }
        return $exceptions;
    }

    /** Gets interfaces in this package.
     * @return classDoc[] An array of interfaces
     */
    function &interfaces() {
        $interfaces = NULL;
        foreach ($this->_classes as $name => $interface) {
            if ($interface->isInterface()) $interfaces[$name] =& $this->_classes[$name];
        }
        return $interfaces;
    }

    /** Gets traits in this package.
     * @return classDoc[] An array of traits
     */
    function &traits() {
        $traits = NULL;
        foreach ($this->_classes as $name => $trait) {
            if ($trait->isTrait()) $traits[$name] =& $this->_classes[$name];
        }
        return $traits;
    }

    /** Gets ordinary classes (excluding exceptions and interfaces) in this package.
     * @return classDoc[] An array of classes
     */
    function &ordinaryClasses() {
        $classes = NULL;
        foreach ($this->_classes as $name => $class) {
            if ($class->isOrdinaryClass()) $classes[$name] =& $this->_classes[$name];
        }
        return $classes;
    }

    /** Gets globals in this package.
     * @return fieldDoc[] An array of globals
     */
    function &globals() {
        return $this->_globals;
    }

    /** Gets functions in this package.
     * @return methodDoc[] An array of functions
     */
    function &functions() {
        return $this->_functions;
    }

    /** Lookups for a class within this package.
     * @param  string   $className Name of the class to lookup
     * @return classDoc            A class
     */
    function &findClass($className) {
        $return = NULL;
        if (isset($this->_classes[$className])) $return =& $this->_classes[$className];
        return $return;
    }
}
