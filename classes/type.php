<?php
/** Represents a PHP variable type.
 * Type can be a class or primitive data type.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/type.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class type {

    /** The name of the type.
     * @var string
     */
    public $name = NULL;

    /** The number of dimensions this type has.
     * @var integer
     */
    public $dimension = 0;

    /** Reference to the root element.
     * @var rootDoc
     */
    public $root = NULL;

    /** Constructor.
     *
     * @param  string  $name The name of the variable type
     * @param  rootDoc $root The rootDoc object to tie this type too
     * @return void
     */
    public function type($name, &$root) {
        while (substr($name, -2) == '[]') {
            $this->dimension++;
            $name = substr($name, 0, -2);
        }
        $this->name = $name;
        $this->root = &$root;
    }

    /** Gets name of this type.
     * @return string Name of type
     */
    public function typeName() {
        return $this->name;
    }

    /** Returns the type's dimension information, as a string.
     * @return string Type's dimention info
     */
    public function dimension() {
        return str_repeat('[]', $this->dimension);
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
        return $this->name.$this->dimension();
    }

    /** Returns this type as a class.
     * @return classDoc A classDoc if the type is a class, null if it is a primitive type
     */
    function &asClassDoc() {
        return $this->root->classNamed($this->name);
    }
}
