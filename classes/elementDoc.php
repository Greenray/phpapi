<?php
/** Represents a PHP program element: global, function, class, interface, field, constructor, or method.
 * This is an abstract class dealing with information common to these elements.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/elementDoc.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 * @abstract
 */

class elementDoc extends doc {

    /** Access type for this element.
     * @var string
     */
    public $access = 'public';

    /** Which source file is this element in.
     * @var string
     */
    public $filename = NULL;

    /** If this element is final.
     * @var boolean
     */
    public $final = FALSE;

    /** The line in the source file this element can be found at.
     * @var integer
     */
    public $lineNumber = NULL;

    /** The elements package.
     * @var string
     */
    public $package = NULL;

    /** Reference to the parent elements.
     * @var doc
     */
    public $parent = NULL;

    /** The source path containing the source file.
     * @var string
     */
    public $sourcePath = NULL;

    /** If this element is static.
     * @var boolean
     */
    public $static = FALSE;

    /** Sets element to have public access. */
    public function makePublic() {
        $this->access = 'public';
    }

    /** Sets element to have protected access. */
    public function makeProtected() {
        $this->access = 'protected';
    }

    /** Sets element to have private access. */
    public function makePrivate() {
        $this->access = 'private';
    }

    /** Gets the containing class of this program element.
     * If the element is in the global scope and does not have a parent class, this will return null.
     *
     * @return classDoc|NULL
     */
    function &containingClass() {
        $return = NULL;
        if (get_class($this->parent) == 'classDoc') $return = &$this->parent;
        return $return;
    }

    /** Gets the package that this program element is contained in.
     * @return packageDoc
     */
    function &containingPackage() {
        return $this->root->packageNamed($this->package);
    }

    /** Gets the name of the package that this program element is contained in.
     * @return string
     */
    public function packageName() {
        return $this->package;
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
        if ($parent && $parent->name() != '' && $this->package != $parent->name())
             return $this->package.'\\'.$parent->name().'\\'.$this->name;
        else return $this->package.'\\'.$this->name;
    }

    /** Gets modifiers string.
     * <pre>
     * Example, for:
     * public abstract integer foo() { ... }
     * modifiers() would return:
     * 'public abstract'
     * </pre>
     *
     * @return string Modifiers
     */
    public function modifiers() {
        $modifiers = $this->access.' ';
        if ($this->final)            $modifiers .= 'final ';
        if (!empty($this->abstract)) $modifiers .= 'abstract ';
        if ($this->static)           $modifiers .= 'static ';

        return $modifiers;
    }

    /** Returns true if this program element is public.
     * @return boolean
     */
    public function isPublic() {
        return ($this->access == 'public') ? TRUE : FALSE;
    }

    /** Returns true if this program element is protected.
     * @return boolean
     */
    public function isProtected() {
        return ($this->access == 'protected') ? TRUE : FALSE;
    }

    /** Returns true if this program element is private.
     * @return boolean
     */
    public function isPrivate() {
        return ($this->access == 'private') ? TRUE : FALSE;
    }

    /** Returns true if this program element is final.
     * @return boolean
     */
    public function isFinal() {
        return $this->final;
    }

    /** Returns true if this program element is static.
     * @return boolean
     */
    public function isStatic() {
        return $this->static;
    }

    /** Gets the source location of this element
     *
     * @return string The source location of this element
     */
    public function location() {
        return $this->sourceFilename().' at line '.$this->sourceLine();
    }

    /** Returns the name of the souce file.
     *
     * @return string The name of the souce file
     */
    public function sourceFilename() {
        $phpapi = $this->root->phpapi();
        return substr($this->filename, strlen($this->sourcePath) + 1);
    }

    /** Returns the line number of the souce code.
     * @return integer Line number of the souce code
     */
    public function sourceLine() {
        return $this->lineNumber;
    }

    /** Returns the element path or NULL.
     *
     * @return string|NULL
     */
    public function asPath() {
        if ($this->isClass() || $this->isInterface() || $this->isTrait() || $this->isException()) {
            return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.$this->name.'.html');

        } elseif ($this->isField()) {
            $class = &$this->containingClass();
            if ($class) return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.$class->name().'.html#').$this->name;
            else        return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-globals.html#').$this->name;

        } elseif ($this->isConstructor() || $this->isMethod()) {
            $class = &$this->containingClass();
            if ($class) return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.$class->name().'.html#').$this->name;
            else        return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-functions.html#').$this->name;

        } elseif ($this->isGlobal())
                                     return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-globals.html#').$this->name;
        elseif ($this->isFunction()) return strtolower(str_replace('.', DS, str_replace('\\', DS, $this->package)).DS.'package-functions.html#').$this->name;

        return NULL;
    }
}
