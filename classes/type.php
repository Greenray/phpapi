<?php
# phpapi: The PHP Documentation Creator

/** Represents a PHP variable type. Type can be a class or primitive data type.
 * @file      classes/type.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   phpapi
 */

class type {

    /** The name of the type.
     * @var string
     */
    public $_name = NULL;

    /** The number of dimensions this type has.
     * @var integer
     */
    public $_dimension = 0;

    /** Reference to the root element.
     * @var rootDoc
     */
    public $_root = NULL;

    /** Constructor.
     * @param  string  $name The name of the variable type
     * @param  RootDoc $root The RootDoc object to tie this type too
     * @return void
     */
    public function type($name, &$root) {
        while (substr($name, -2) == '[]') {
            $this->_dimension++;
            $name = substr($name, 0, -2);
        }
        $this->_name = $name;
        $this->_root = & $root;
    }

    /** Gets name of this type.
     * @return string Name of type
     */
    public function typeName() {
        return $this->_name;
    }

    /** Returns the type's dimension information, as a string.
     * @return string Type's dimention info
     */
    public function dimension() {
        return str_repeat('[]', $this->_dimension);
    }

    /** Gets qualified name of this type.
     * @return string Qualified naame of the type
     * @todo This method is still to be implemented
     */
    public function qualifiedTypeName() {
        return $this->typeName();
    }

    /** Returns a string representation of the type.
     * @return string String representation of the type
     */
    public function toString() {
        return $this->_name.$this->dimension();
    }

    /** Returns this type as a class.
     * @return ClassDoc A classDoc if the type is a class, null if it is a primitive type
     */
    function &asClassDoc() {
        return $this->_root->classNamed($this->_name);
    }
}
