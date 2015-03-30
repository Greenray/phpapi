<?php
# phpapi: The PHP Documentation Creator

require_once 'seeTag.php';

/** Represents a throws tag.
 *
 * @file      classes/throwsTag.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Tags
 */

class throwsTag extends seeTag {

    /** Constructor.
     * @param  string  $text The contents of the tag
     * @param  array   $data Reference to doc comment data array
     * @param  rootDoc $root The root object
     * @return void
     */
    public function throwsTag($text, &$data, &$root) {
        $explode     = preg_split('/[ \t]+/', $text);
        $this->_link = array_shift($explode);
        $data['throws'][$this->_link] = $this->_link;
        parent::tag('@throws', join(' ', $explode), $root);
    }

    /** Gets display name of this tag.
     * @return string 'Throws'
     */
    public function displayName() {
        return 'Throws';
    }

    /** Gets value of this tag.
     * @param Doclet doclet
     * @return string
     */
    public function text($doclet) {
        return $this->_linkText($this->_link, $doclet).($this->_text ? ' + '.$this->_text : '');
    }

    /** Returns true if this Taglet is used in constructor documentation.
     * @return boolean TRUE
     */
    public function inConstructor() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in field documentation.
     * @return boolean FALSE
     */
    public function inField() {
        return FALSE;
    }

    /** Returns true if this Taglet is used in method documentation.
     * @return boolean TRUE
     */
    public function inMethod() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in overview documentation.
     * @return boolean FALSE
     */
    public function inOverview() {
        return FALSE;
    }

    /** Returns true if this Taglet is used in package documentation.
     * @return boolean FALSE
     */
    public function inPackage() {
        return FALSE;
    }

    /** Returns true if this Taglet is used in class or interface documentation.
     * @return bool
     */
    public function inType() {
        return FALSE;
    }

    /** Returns true if this Taglet is an inline tag.
     * @return bool
     */
    public function isInlineTag() {
        return FALSE;
    }
}
