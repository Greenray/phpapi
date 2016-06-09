<?php
/**
 * Represents a PHP program element: global, function, class, interface, field, constructor, or method.
 * This is an abstract class dealing with information common to these elements.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/classes/elementDoc.php
 * @package   phpapi
 * @abstract
 */

class elementDoc extends doc {

    /** @var string Access type for this element */
    public $access = 'public';

    /** @var string Which source file is this element in */
    public $filename = NULL;

    /** @var boolean If this element is final */
    public $final = FALSE;

    public $includes = [];

    /** @var integer Line in the source file this element can be found at */
    public $lineNumber = NULL;

    /** @var string Elements package */
    public $package = NULL;

    /** @var fieldDoc Parameters this function takes */
    public $parameters = [];

    /** @var doc Reference to the parent elements */
    public $parent = NULL;

    /** @var string Source path containing the source file */
    public $sourcePath = NULL;

    /** @var boolean If this element is static */
    public $static = FALSE;

    /** @var classDoc Exceptions this function throws */
    public $throws = [];

    /** Constructor. */
    public function __construct() {}

    /**
     * Gets aruments of the element.
     * Return a string whith the list of arguments with their types.
     * <pre>
     * For a function
     *      method($x, $y, &$o), where x is mixed, y is integer and o is a reference to object
     * it will return
     *      method(mixed $x, integer $y, object &$o)
     * </pre>
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     *
     * @return string
     */
    public function arguments() {
        $args    = '';
        $package = &$this->containingPackage();
        foreach ($this->parameters as $param) {
            $classDoc = &$param->type->isClass();
            if ($classDoc)
                 $args .= '<a href="'.str_repeat('../', $package->depth() + 1).$classDoc->path().'">'.$classDoc->name.'</a> <span class="blue">'.$param->name.'</span>, ';
            else $args .= '<span class="lilac">'.$param->type->typeName.'</span> <span class="blue">'.$param->name.'</span>, ';
        }
        return '('.substr($args, 0, -2).')';
    }

    /**
     * Returns the element path or NULL.
     *
     * @return string|NULL
     */
    public function path() {
        if ($this->isClass() || $this->isInterface() || $this->isTrait() || $this->isException()) {
            return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.$this->name.'.html');

        } elseif ($this->isField()) {
            $class = &$this->containingClass();
            if ($class)
                 return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.$class->name.'.html#').$this->name;
            else return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-globals.html#').$this->name;

        } elseif ($this->isConstructor() || $this->isMethod()) {
            $class = &$this->containingClass();
            if ($class)
                 return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.$class->name.'.html#').$this->name;
            else return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-functions.html#').$this->name;

        } elseif ($this->isGlobal()) return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-globals.html#').$this->name;
        elseif ($this->isFunction()) return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-functions.html#').$this->name;

        return NULL;
    }

    /**
     * Gets the containing class of this program element.
     * If the element is in the global scope and does not have a parent class, this will return NULL.
     *
     * @return classDoc|NULL
     */
    function &containingClass() {
        $return = NULL;
        if (get_class($this->parent) === 'classDoc') $return = &$this->parent;
        return $return;
    }

    /**
     * Gets the package that this program element is contained in.
     *
     * @return packageDoc
     */
    function &containingPackage() {
        return $this->root->packageNamed($this->package);
    }

    /**
     * Gets the fully qualified name.
     * <pre>
     * Example:
     * for the method bar() in class Foo in the package Baz, return:
     * Baz\Foo\bar()
     * </pre>
     *
     * @return string
     */
    public function fullNamespace() {
        $parent = &$this->containingClass();
        if ($parent && ($parent->name !== '') && ($this->package !== $parent->name))
             return $this->package.' \\ '.$parent->name.' \\ '.$this->name;
        else return $this->package.' \\ '.$this->name;
    }

    /**
     * Gets the source location of this element
     *
     * @return string
     */
    public function location() {
        return substr($this->filename, mb_strlen($this->sourcePath)).' at line '.$this->lineNumber;
    }

    /**
     * Gets modifiers string.
     * <pre>
     * Example, for:
     * public abstract integer foo() { ... }
     * modifiers() would return:
     * 'public abstract'
     * </pre>
     *
     * @return string
     */
    public function modifiers() {
        $modifiers = $this->access.' ';
        if ($this->final)            $modifiers .= 'final ';
        if (!empty($this->abstract)) $modifiers .= 'abstract ';
        if ($this->static)           $modifiers .= 'static ';

        return $modifiers;
    }
}
