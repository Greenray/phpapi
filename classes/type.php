<?php
/**
 * Represents a PHP variable type.
 * Type can be a class or primitive data type.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      classes/type.php
 * @package   phpapi
 */

class type {

    /** @var rootDoc Reference the root element */
    public $root = NULL;

    /** @var string Name of the type */
    public $typeName = NULL;

    /**
     * Constructor.
     *
     * @param string  $name  Name of the variable type
     * @param rootDoc &$root Reference rootDoc object to tie this type too
     */
    public function __construct($name, &$root) {
        while (substr($name, -2) === '[]') {
            $name = substr($name, 0, -2);
        }
        $this->typeName = $name;
        $this->root     = &$root;
    }

    /**
     * Returns a classDoc if the type is a class, NULL if it is a primitive type.
     *
     * @return classDoc|NULL
     */
    function &asClassDoc() {
        return $this->root->classNamed($this->typeName);
    }
}
