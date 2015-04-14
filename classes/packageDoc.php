<?php
/** Represents a PHP package.
 * Provides access to information about the package,
 * the package's comment and tags, and the classes in the package.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/packageDoc.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class packageDoc extends doc {

    /** @var classDoc The classes in this package */
    public $classes = [];

    /** @var fieldDoc The globals in this package */
    public $globals = [];

    /** @var methodDoc The functions in this package */
    public $functions = [];

    /** Constructor.
     * @param string  $name     Package name
     * @param rootDoc &$root    The reference to the root element
     */
    public function __construct($name, &$root) {
        $this->name =  $name;
        $this->root = &$root;
    }

    /** Adds a class to this package.
     * @param classDoc &$class The reference to the class
     */
    public function addClass(&$class) {
        if (isset($this->classes[$class->name])) {
            $this->root->phpapi->warning(LF.'Found class '.$class->name.' again, overwriting previous version');
        }
        $this->classes[$class->name] = &$class;
    }

    /** Adds a global to this package.
     * @param fieldDoc &$global The reference to the global element
     */
    public function addGlobal(&$global) {
        if (!isset($this->globals[$global->name])) $this->globals[$global->name] = &$global;
    }

    /** Returns the package path.
     * @return string
     */
    public function asPath() {
        return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->name)));
    }

    /** Calculates the depth of this package from the root.
     * @return integer
     */
    public function depth() {
        $depth  = substr_count($this->name, '.');
        $depth += substr_count($this->name, '\\');
        $depth += substr_count($this->name, '/');
        return $depth;
    }

    /** Gets exceptions in this package.
     * @return array|NULL
     */
    public function &exceptions() {
        $exceptions = NULL;
        foreach ($this->classes as $name => $exception) {
            if ($exception->isException()) $exceptions[$name] = &$this->classes[$name];
        }
        return $exceptions;
    }

    /** Lookups for a class within this package.
     * @param  string $className Name of the class to lookup
     * @return classDoc|NULL
     */
    public function &findClass($className) {
        $return = NULL;
        if (isset($this->classes[$className])) $return = &$this->classes[$className];
        return $return;
    }

    /** Gets interfaces in this package.
     * @return array|NULL
     */
    public function &interfaces() {
        $interfaces = NULL;
        foreach ($this->classes as $name => $interface) {
            if ($interface->interface) $interfaces[$name] = &$this->classes[$name];
        }
        return $interfaces;
    }

    /** Gets ordinary classes (excluding exceptions and interfaces) in this package.
      * @return array|NULL
     */
    public function &ordinaryClasses() {
        $classes = NULL;
        foreach ($this->classes as $name => $class) {
            if ($class->isOrdinaryClass()) $classes[$name] = &$this->classes[$name];
        }
        if (!empty($classes)) ksort($classes);
        return $classes;
    }

    /** Gets traits in this package.
     * @return array|NULL
     */
    public function &traits() {
        $traits = NULL;
        foreach ($this->classes as $name => $trait) {
            if ($trait->trait) $traits[$name] = &$this->classes[$name];
        }
        return $traits;
    }
}
