<?php
/** Represents a PHP variable, constant or member variable (field).
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/fieldDoc.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class fieldDoc extends elementDoc {

    /** The type of the variable.
     * @var string
     */
    public $type = NULL;

    /** The value of the variable if it is a constant.
     * @var mixed
     */
    public $value = NULL;

    /** Constructor.
     *
     * @param  string             $name       Name of this element
     * @param  classDoc|methodDoc $parent     The parent of this element
     * @param  rootDoc            $root       The root element
     * @param  string             $filename   The filename of the source file this element is in
     * @param  integer            $lineNumber The line number of the source file this element is at
     * @param  string             $sourcePath The source path containing the source file
     * @return void
     */
    public function fieldDoc($name, &$parent, &$root, $filename = NULL, $lineNumber = NULL, $sourcePath = NULL) {
        $this->name       = trim($name, '$\'"');
        $this->parent     = &$parent;
        $this->root       = &$root;
        $this->type       = &new type('mixed', $root);
        $this->filename   = $filename;
        $this->lineNumber = $lineNumber;
        $this->sourcePath = $sourcePath;
    }

    /** Gets type of this variable.
     * @return type The type of the variable
     */
    function &type() {
        return $this->type;
    }

    /** Construct is a field.
     * @return boolean
     */
    public function isField() {
        return !$this->isGlobal();
    }

    /** Construct is a global.
     *
     * @return boolean
     */
    public function isGlobal() {
        return (get_class($this->parent) == 'rootDoc') ? TRUE : FALSE;
    }

    /** Format a field type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     *
     * @return string The string representation of the field type
     */
    public function typeAsString() {
        $myPackage = &$this->containingPackage();
        $classDoc  = &$this->type->asClassDoc();
        if ($classDoc) {
               $packageDoc = &$classDoc->containingPackage();
               return '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name().$this->type->dimension().'</a>';
        } else return $this->type->typeName().$this->type->dimension();
    }

    /** Returns the value of the constant.
     *
     * @return mixed|NULL Constant value
     */
    public function constantValue() {
        return ($this->final) ? $this->value : NULL;
    }
}
