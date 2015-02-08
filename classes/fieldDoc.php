<?php
# phpapi: The PHP Documentation Creator

/** Represents a PHP variable, constant or member variable (field).
 * @file      classes/fieldDoc.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   phpapi
 */

class fieldDoc extends ProgramElementDoc {

    /** The type of the variable.
     * @var type
     */
    public $_type = NULL;

    /** The value of the variable if it is a constant.
     * @var mixed
     */
    public $_value = NULL;

    /** Constructor.
     * @param str name Name of this element
     * @param ClassDoc|MethodDoc parent The parent of this element
     * @param RootDoc root The root element
     * @param str filename The filename of the source file this element is in
     * @param int lineNumber The line number of the source file this element is at
     * @param str sourcePath The source path containing the source file
     * @return void
     */
    public function fieldDoc($name, &$parent, &$root, $filename = NULL, $lineNumber = NULL, $sourcePath = NULL) {
        $this->_name   = trim($name, '$\'"');
        $this->_parent = & $parent;   # Set reference to parent
        $this->_root   = & $root;     # Set reference to root
        $this->_type   = & new type('mixed', $root);
        $this->_filename = $filename;
        $this->_lineNumber = $lineNumber;
        $this->_sourcePath = $sourcePath;
    }

    /** Get type of this variable.
     * @return Type
     */
    function &type() {
        return $this->_type;
    }

    /** Returns the value of the field.
     * @return mixed
     */
    public function value() {
        return $this->_value;
    }

    /** Construct is a field.
     * @return bool
     */
    public function isField() {
        return !$this->isGlobal();
    }

    /** Construct is a global.
     * @return bool
     */
    public function isGlobal() {
        return (strtolower(get_class($this->_parent)) == 'rootdoc') ? TRUE : FALSE;
    }

    /** Format a field type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     * @return str The string representation of the field type
     */
    public function typeAsString() {
        $myPackage = & $this->containingPackage();
        $classDoc  = & $this->_type->asClassDoc();
        if ($classDoc) {
            $packageDoc = & $classDoc->containingPackage();
            return '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name().$this->_type->dimension().'</a>';
        } else {
            return $this->_type->typeName().$this->_type->dimension();
        }
    }

    /** Returns the value of the constant.
     * @return str
     */
    public function constantValue() {
        return ($this->_final) ? $this->_value : NULL;
    }
}
