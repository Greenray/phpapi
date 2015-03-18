<?php
# phpapi: The PHP Documentation Creator

/** Represents a parameter tag.
 * @file      classes/paramTag.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Tags
 */

class paramTag extends tag {

    /** The variable name of the parameter.
     * @var string
     */
    public $_var = NULL;

    /** Constructor.
     * @param string text The contents of the tag
     * @param array data Reference to doc comment data array
     * @param rootDoc root The root object
     */
    public function paramTag($text, &$data, &$root) {
        $explode = preg_split('/[ \t]+/', $text);
        $type = array_shift($explode);
        if ($type) {
            $this->_var = trim(array_shift($explode), '$');
            if ($this->_var) {
                $data['parameters'][$this->_var]['type'] = $type;
            } else {
                $count = isset($data['parameters']) ? count($data['parameters']) : 0;
                $data['parameters']['__unknown'.$count]['type'] = $type;
            }
            $text = join(' ', $explode);
        }
        if ($text !== '') {
               parent::tag('@param', '$'.$this->_var.'+'.$text, $root, $type);
        } else parent::tag('@param', NULL, $root);
    }

    /** Get display name of this tag.
     * @return str
     */
    public function displayName() {
        return 'Parameters';
    }

    /** Return true if this Taglet is used in constructor documentation.
     * @return bool
     */
    public function inConstructor() {
        return TRUE;
    }

    /** Return true if this Taglet is used in field documentation.
     * @return bool
     */
    public function inField() {
        return FALSE;
    }

    /** Return true if this Taglet is used in method documentation.
     * @return bool
     */
    public function inMethod() {
        return TRUE;
    }

    /** Return true if this Taglet is used in overview documentation.
     * @return bool
     */
    public function inOverview() {
        return FALSE;
    }

    /** Return true if this Taglet is used in package documentation.
     * @return bool
     */
    public function inPackage() {
        return FALSE;
    }

    /** Return true if this Taglet is used in class or interface documentation.
     * @return bool
     */
    public function inType() {
        return FALSE;
    }

    /** Return true if this Taglet is an inline tag.
     * @return bool
     */
    public function isInlineTag() {
        return FALSE;
    }

    /** Return true if this Taglet should be outputted even if it has no text content.
     * @return bool
     */
    public function displayEmpty() {
        return FALSE;
    }
}
