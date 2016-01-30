<?php
/**
 * Represents a PHP function or method (member function).
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      classes/methodDoc.php
 * @package   phpapi
 */

class methodDoc extends elementDoc {

    /** @var boolean Is this method abstract */
    public $abstract = FALSE;

    /** @var array Required files or files to be included */
    public $includes = [];

    /** @var type Type of returns variable */
    public $returnType;

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
    public function __construct($name, &$parent, &$root, $filename, $lineNumber, $sourcePath) {
        $this->name       = $name;
        $this->parent     = &$parent;
        $this->root       = &$root;
        $this->returnType = &new type('void', $root);
        $this->filename   = $filename;
        $this->lineNumber = $lineNumber;
        $this->sourcePath = $sourcePath;
    }

    /**
     * Returns TRUE if this class is an constructor.
     *
     * @return boolean
     */
    public function isConstructor() {
        return $this->name === '__construct';
    }

    /**
     * Returns TRUE if this class is an destructor.
     *
     * @return boolean
     */
    public function isDestructor() {
        return $this->name === '__destruct';
    }

    /**
     * Is this construct a function.
     *
     * @return boolean
     */
    public function isFunction() {
        return (get_class($this->parent) === 'rootDoc' && !$this->containingClass()) ? TRUE : FALSE;
    }

    /**
     * Constructs a method.
     * False until overridden.
     *
     * @return boolean
     */
    public function isMethod() {
        return !$this->isFunction();
    }

    /**
     * Formats a return type for outputting.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     *
     * @return string
     */
    public function returnType() {
        $myPackage = &$this->containingPackage();
        $classDoc  = &$this->returnType->asClassDoc();
        if ($classDoc) {
               return '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name.'</a>';
        } else return $this->returnType->typeName;
    }
}
