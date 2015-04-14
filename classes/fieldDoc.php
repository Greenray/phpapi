<?php
/** Represents a PHP variable, constant or member variable (field).
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/fieldDoc.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class fieldDoc extends elementDoc {

    /** @var string The type of the variable */
    public $type = NULL;

    /** @var mixed The value of the variable if it is a constant */
    public $value = NULL;

    /** Constructor.
     * @param  string             $name       Name of this element
     * @param  classDoc|methodDoc &$parent    The reference to the parent of this element
     * @param  rootDoc            &$root      The reference to the root element
     * @param  string             $filename   The filename of the source file this element is in
     * @param  integer            $lineNumber The line number of the source file this element is at
     * @param  string             $sourcePath The source path containing the source file
     */
    public function __construct($name, &$parent, &$root, $filename = NULL, $lineNumber = NULL, $sourcePath = NULL) {
        $this->name       = trim($name, '\'"');
        $this->parent     = &$parent;
        $this->root       = &$root;
        $this->type       = &new type('mixed', $root);
        $this->filename   = $filename;
        $this->lineNumber = $lineNumber;
        $this->sourcePath = $sourcePath;
    }

    /** Construct is a field.
     * @return boolean
     */
    public function isField() {
        return !$this->isGlobal();
    }

    /** Construct is a global.
     * @return boolean
     */
    public function isGlobal() {
        return (get_class($this->parent) === 'rootDoc') ? TRUE : FALSE;
    }

    /** Format a field type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     * @return string
     */
    public function typeAsString() {
        $package  = &$this->containingPackage();
        $classDoc = &$this->type->asClassDoc();
        if ($classDoc) {
               return '<a href="'.str_repeat('../', $package->depth() + 1).$classDoc->asPath().'">'.$classDoc->name.'</a>';
        } else return $this->type->typeName;
    }
}
