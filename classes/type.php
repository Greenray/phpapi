<?php
/** Represents a PHP variable type.
 * Type can be a class or primitive data type.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/type.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class type {

    /** @var rootDoc The reference the root element */
    public $root = NULL;

    /** @var string The name of the type */
    public $typeName = NULL;

    /** Constructor.
     * @param string  $name  The name of the variable type
     * @param rootDoc &$root The reference rootDoc object to tie this type too
     */
    public function __construct($name, &$root) {
        while (substr($name, -2) === '[]') {
            $name = substr($name, 0, -2);
        }
        $this->typeName = $name;
        $this->root     = &$root;
    }

    /** Returns a classDoc if the type is a class, NULL if it is a primitive type.
     * @return classDoc|NULL
     */
    function &asClassDoc() {
        return $this->root->classNamed($this->typeName);
    }
}
