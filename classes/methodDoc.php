<?php
# phpapi: The PHP Documentation Creator

/** Represents a PHP function or method (member function).
 *
 * @file      classes/methodDoc.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   phpapi
 */

class methodDoc extends ExecutableDoc {

    /** The type of variable this method returns.
     * @var type
     */
    public $_returnType;

    /** Is this class abstract.
     * @var boolean
     */
    public $_abstract = FALSE;

    /** Constructor.
     *
     * @param string name Name of this element
     * @param classDoc|methodDoc parent The parent of this element
     * @param rootDoc root The root element
     * @param string filename The filename of the source file this element is in
     * @param integer lineNumber The line number of the source file this element is at
     * @param string sourcePath The source path containing the source file
     */
    public function methodDoc($name, &$parent, &$root, $filename, $lineNumber, $sourcePath) {
        $this->_name       = $name;
        $this->_parent     =& $parent; # set reference to parent
        $this->_root       =& $root; # set reference to root
        $this->_returnType =& new type('void', $root);
        $this->_filename   = $filename;
        $this->_lineNumber = $lineNumber;
        $this->_sourcePath = $sourcePath;
    }

    /** Add a parameter to this method.
     * @param fieldDoc parameter
     */
    public function addParameter(&$parameter) {
        $this->_parameters[$parameter->name()] =& $parameter;
    }

    /** Get return type.
     * @return Type
     */
    public function returnType() {
        return $this->_returnType;
    }

    /** Format a return type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     *
     * @return string The string representation of the return type
     */
    public function returnTypeAsString() {
        $myPackage =& $this->containingPackage();
        $classDoc =& $this->_returnType->asclassDoc();
        if ($classDoc) {
               $packageDoc =& $classDoc->containingPackage();
               return '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name().$this->_returnType->dimension().'</a>';
        } else return $this->_returnType->typeName().$this->_returnType->dimension();
    }

    /** Is this construct a function.
     * @return bool
     */
    public function isFunction() {
        return (get_class($this->_parent) == 'rootDoc' && !$this->containingClass()) ? TRUE : FALSE;
    }

    /** Is this construct a method.
     * @return bool
     */
    public function isMethod() {
        return !$this->isFunction();
    }

    /** Return true if this class is abstract.
     * @return bool
     */
    public function isAbstract() {
        return $this->_abstract;
    }

    /** Return true if this class is an constructor.
     * @return bool
     */
    public function isConstructor() {
        return $this->_name == '__construct';
    }

    /** Return true if this class is an destructor.
     * @return bool
     */
    public function isDestructor() {
        return $this->_name == '__destruct';
    }
}
