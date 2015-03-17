<?php
# phpapi: The PHP Documentation Creator

/** Represents a PHP program element: global, function, class, interface,
 * field, constructor, or method. This is an abstract class dealing with
 * information common to these elements.
 *
 * @file      classes/programElementDoc.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   phpapi
 * @abstract
 */

class programElementDoc extends Doc {

    /** Reference to the elements parent.
     * @var doc
     */
    public $_parent = NULL;

    /** The elements package.
     * @var string
     */
    public $_package = NULL;

    /** If this element is final.
     * @var boolean
     */
    public $_final = FALSE;

    /** Access type for this element.
     * @var string
     */
    public $_access = 'public';

    /** If this element is static.
     * @var boolean
     */
    public $_static = FALSE;

    /** Which source file is this element in.
     * @var string
     */
    public $_filename = NULL;

    /** The line in the source file this element can be found at.
     * @var integer
     */
    public $_lineNumber = NULL;

    /** The source path containing the source file.
     * @var string
     */
    public $_sourcePath = NULL;

    /** Sets element to have public access. */
    public function makePublic() {
        $this->_access = 'public';
    }

    /** Sets element to have protected access. */
    public function makeProtected() {
        $this->_access = 'protected';
    }

    /** Sets element to have private access. */
    public function makePrivate() {
        $this->_access = 'private';
    }

    /** Gets the containing class of this program element.
     * If the element is in the global scope and does not have a parent class, this will return null.
     * @return classDoc|NULL
     */
    function &containingClass() {
        $return = NULL;
        if (get_class($this->_parent) == 'classDoc') $return =& $this->_parent;
        return $return;
    }

    /** Gets the package that this program element is contained in.
     * @return packageDoc
     */
    function &containingPackage() {
        return $this->_root->packageNamed($this->_package);
    }

    /** Gets the name of the package that this program element is contained in.
     * @return string
     */
    public function packageName() {
        return $this->_package;
    }

    /** Gets the fully qualified name.
     *
     * <pre>
     * Example:
     * for the method bar() in class Foo in the package Baz, return:
     * Baz\Foo\bar()
     * </pre>
     *
     * @return string
     */
    public function qualifiedName() {
        $parent =& $this->containingClass();
        if ($parent && $parent->name() != '' && $this->_package != $parent->name())
             return $this->_package.'\\'.$parent->name().'\\'.$this->_name;
        else return $this->_package.'\\'.$this->_name;
    }

    /** Gets modifiers string.
     *
     * <pre>
     * Example, for:
     * public abstract integer foo() { ... }
     * modifiers() would return:
     * 'public abstract'
     * </pre>
     *
     * @return string Modifiers
     */
    public function modifiers($showPublic = TRUE) {
        $modifiers = '';
        if ($showPublic || $this->_access != 'public') $modifiers .= $this->_access.' ';
        if ($this->_final)           $modifiers .= 'final ';
        if (isset($this->_abstract)) $modifiers .= 'abstract ';
        if ($this->_static)          $modifiers .= 'static ';

        return $modifiers;
    }

    /** Returns true if this program element is public.
     * @return boolean
     */
    public function isPublic() {
        return ($this->_access == 'public') ? TRUE : FALSE;
    }

    /** Returns true if this program element is protected.
     * @return boolean
     */
    public function isProtected() {
        return ($this->_access == 'protected') ? TRUE : FALSE;
    }

    /** Returns true if this program element is private.
     * @return boolean
     */
    public function isPrivate() {
        return ($this->_access == 'private') ? TRUE : FALSE;
    }

    /** Returns true if this program element is final.
     * @return boolean
     */
    public function isFinal() {
        return $this->_final;
    }

    /** Returns true if this program element is static.
     * @return boolean
     */
    public function isStatic() {
        return $this->_static;
    }

    /** Gets the source location of this element
     * @return string The source location of this element
     */
    public function location() {
        return $this->sourceFilename().' at line '.$this->sourceLine();
    }

    /** Returns the name of the souce file.
     * @return string The name of the souce file
     */
    public function sourceFilename() {
        $phpapi = $this->_root->phpapi();
        return substr($this->_filename, strlen($this->_sourcePath) + 1);
    }

    /** Returns the line number of the souce code.
     * @return integer Line number of the souce code
     */
    public function sourceLine() {
        return $this->_lineNumber;
    }

    /** Returns the element path or NULL.
     * @return string|NULL
     */
    public function asPath() {
        if ($this->isClass() || $this->isInterface() || $this->isTrait() || $this->isException()) {
            return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_package)).'/'.$this->_name.'.html');
        } elseif ($this->isField()) {
            $class =& $this->containingClass();
            if ($class) return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_package)).'/'.$class->name().'.html#').$this->_name;
            else        return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_package)).'/package-globals.html#').$this->_name;
        } elseif ($this->isConstructor() || $this->isMethod()) {
            $class =& $this->containingClass();
            if ($class) return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_package)).'/'.$class->name().'.html#').$this->_name.'()';
            else        return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_package)).'/package-functions.html#').$this->_name.'()';
        } elseif ($this->isGlobal())
                                     return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_package)).'/package-globals.html#').$this->_name;
        elseif ($this->isFunction()) return strtolower(str_replace('.', '/', str_replace('\\', '/', $this->_package)).'/package-functions.html#').$this->_name.'()';

        return NULL;
    }
}
