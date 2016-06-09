<?php
/**
 * Represents a PHP variable, constant or member variable (field).
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/classes/fieldDoc.php
 * @package   phpapi
 */

class fieldDoc extends elementDoc {

    /** @var string Type of the variable */
    public $type = NULL;

    /** @var mixed Value of the variable */
    public $value = NULL;

    /**
     * Constructor.
     *
     * @param string             $name       Name of this element
     * @param classDoc|methodDoc &$parent    Reference to the parent of this element
     * @param rootDoc            &$root      Reference to the root element
     * @param string             $filename   Filename of the source file this element is in
     * @param integer            $lineNumber Line number of the source file this element is at
     * @param string             $sourcePath Source path containing the source file
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

    /**
     * Construct is a field.
     *
     * @return boolean
     */
    public function isField() {
        return !$this->isGlobal();
    }

    /**
     * Construct is a global.
     *
     * @return boolean
     */
    public function isGlobal() {
        return (get_class($this->parent) === 'rootDoc') ? TRUE : FALSE;
    }

    /**
     * Formats a field type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     *
     * @return string
     */
    public function type() {
        $package  = &$this->containingPackage();
        $classDoc = &$this->type->isClass();
        if ($classDoc) {
               return '<a href="'.str_repeat('../', $package->depth() + 1).$classDoc->path().'">'.$classDoc->name.'</a>';
        } else return $this->type->typeName;
    }
}
