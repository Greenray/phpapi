<?php
/** Represents a PHP program element: global, function, class, interface, field, constructor, or method.
 * This is an abstract class dealing with information common to these elements.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/elementDoc.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
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

    /** @var integer The line in the source file this element can be found at */
    public $lineNumber = NULL;

    /** @var string The elements package */
    public $package = NULL;

    /** @var fieldDoc The parameters this function takes */
    public $parameters = [];

    /** @var doc The reference the parent elements */
    public $parent = NULL;

    /** @var string The source path containing the source file */
    public $sourcePath = NULL;

    /** @var boolean If this element is static */
    public $static = FALSE;

    /** @var classDoc The exceptions this function throws */
    public $throws = [];

    /** Constructor. */
    public function __construct() {}

    /** Returns the element path or NULL.
     * @return string|NULL
     */
    public function asPath() {
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

    /** Gets the containing class of this program element.
     * If the element is in the global scope and does not have a parent class, this will return null.
     * @return classDoc|NULL
     */
    function &containingClass() {
        $return = NULL;
        if (get_class($this->parent) === 'classDoc') $return = &$this->parent;
        return $return;
    }

    /** Gets the package that this program element is contained in.
     * @return packageDoc
     */
    function &containingPackage() {
        return $this->root->packageNamed($this->package);
    }

    /** Gets the source location of this element
     * @return string
     */
    public function location() {
        return substr($this->filename, strlen($this->sourcePath) + 1).' at line '.$this->lineNumber;
    }

    /** Gets the fully qualified name.
     * <pre>
     * Example:
     * for the method bar() in class Foo in the package Baz, return:
     * Baz\Foo\bar()
     * </pre>
     *
     * @return string
     */
    public function qualifiedName() {
        $parent = &$this->containingClass();
        if ($parent && ($parent->name !== '') && ($this->package !== $parent->name))
             return $this->package.' \\ '.$parent->name.' \\ '.$this->name;
        else return $this->package.' \\ '.$this->name;
    }

    /** Gets modifiers string.
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

    /** Gets signature.
     * Return a string which is the flat signiture of this function.
     * It is the parameter list, type is not qualified.
     * <pre>
     * for a function
     *      mymethod(foo x, integer y)
     * it will return
     *      (foo x, integer y)
     * </pre>
     * Recognised types are turned into HTML anchor tags to the documentation
     * page for the class defining them.
     *
     * @return string
     */
    public function signature() {
        $signature = '';
        $package   = &$this->containingPackage();
        foreach ($this->parameters as $param) {
            $classDoc = &$param->type->asClassDoc();
            if ($classDoc)
                 $signature .= '<a href="'.str_repeat('../', $package->depth() + 1).$classDoc->asPath().'">'.$classDoc->name.'</a> <span class="blue">'.$param->name.'</span>, ';
            else $signature .= '<span class="lilac">'.$param->type->typeName.'</span> <span class="blue">'.$param->name.'</span>, ';
        }
        return '('.substr($signature, 0, -2).')';
    }
}
