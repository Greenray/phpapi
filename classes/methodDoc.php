<?php
/** Represents a PHP function or method (member function).
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/methodDoc.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class methodDoc extends elementDoc {

    /** @var boolean Is this method abstract */
    public $abstract = FALSE;

    /** @var type The type of returns variable */
    public $returnType;

    /** Constructor.
     * @param  string             $name       Name of this element
     * @param  classDoc|methodDoc &$parent    The reference to the parent of this element
     * @param  rootDoc            &$root      The reference to the root element
     * @param  string             $filename   The filename of the source file this element is in
     * @param  integer            $lineNumber The line number of the source file this element is at
     * @param  string             $sourcePath The source path containing the source file
     */
    public function __construct($name, &$parent, &$root, $filename, $lineNumber, $sourcePath) {
        $this->name       = $name;
        $this->parent     = &$parent;
        $this->root       = &$root;
        $this->returnType = &new type('void', $root);
        $this->filename   = $filename;
        $this->lineNumber = $lineNumber;
        $this->sourcePath = $sourcePath;
    }

    /** Returns TRUE if this class is an constructor.
     * @return boolean
     */
    public function isConstructor() {
        return $this->name === '__construct';
    }

    /** Returns TRUE if this class is an destructor.
     * @return boolean
     */
    public function isDestructor() {
        return $this->name === '__destruct';
    }

    /** Is this construct a function.
     * @return boolean
     */
    public function isFunction() {
        return (get_class($this->parent) === 'rootDoc' && !$this->containingClass()) ? TRUE : FALSE;
    }

    /** Is this construct a method.
     * @return boolean
     */
    public function isMethod() {
        return !$this->isFunction();
    }

    /** Formats a return type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     * @return string
     */
    public function returnTypeAsString() {
        $myPackage = &$this->containingPackage();
        $classDoc  = &$this->returnType->asClassDoc();
        if ($classDoc) {
               return '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name.'</a>';
        } else return $this->returnType->typeName;
    }
}
