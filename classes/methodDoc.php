<?php
/** Represents a PHP function or method (member function).
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/methodDoc.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class methodDoc extends executableDoc {

    /** The type of returns variable.
     * @var type
     */
    public $returnType;

    /** Is this method abstract.
     * @var boolean
     */
    public $abstract = FALSE;

    /** Constructor.
     *
     * @param  string name Name of this element
     * @param  classDoc|methodDoc $parent     The parent of this element
     * @param  rootDoc            $root       The root element
     * @param  string             $filename   The filename of the source file this element is in
     * @param  integer            $lineNumber The line number of the source file this element is at
     * @param  string             $sourcePath The source path containing the source file
     * @return void
     */
    public function methodDoc($name, &$parent, &$root, $filename, $lineNumber, $sourcePath) {
        $this->name       = $name;
        $this->parent     = &$parent;
        $this->root       = &$root;
        $this->returnType = &new type('void', $root);
        $this->filename   = $filename;
        $this->lineNumber = $lineNumber;
        $this->sourcePath = $sourcePath;
    }

    /** Adds a parameter to this method.
     *
     * @param  fieldDoc $parameter Name of the method parameter
     * @return void
     */
    public function addParameter(&$parameter) {
        $this->parameters[$parameter->name()] = &$parameter;
    }

    /** Gets return type.
     *
     * @return string Type of method return value
     */
    public function returnType() {
        return $this->returnType;
    }

    /** Formats a return type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     *
     * @return string The string representation of the return type
     */
    public function returnTypeAsString() {
        $myPackage = &$this->containingPackage();
        $classDoc  = &$this->returnType->asClassDoc();
        if ($classDoc) {
               $packageDoc = &$classDoc->containingPackage();
               return '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name().$this->returnType->dimension().'</a>';
        } else return $this->returnType->typeName().$this->returnType->dimension();
    }

    /** Is this construct a function.
     * @return bool
     */
    public function isFunction() {
        return (get_class($this->parent) == 'rootDoc' && !$this->containingClass()) ? TRUE : FALSE;
    }

    /** Is this construct a method.
     * @return bool
     */
    public function isMethod() {
        return !$this->isFunction();
    }

    /** Returns true if this class is abstract.
     * @return bool
     */
    public function isAbstract() {
        return $this->abstract;
    }

    /** Returns true if this class is an constructor.
     * @return bool
     */
    public function isConstructor() {
        return $this->name == '__construct';
    }

    /** Returns true if this class is an destructor.
     * @return bool
     */
    public function isDestructor() {
        return $this->name == '__destruct';
    }
}
