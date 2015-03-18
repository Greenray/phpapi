<?php
# phpapi: The PHP Documentation Creator

require_once 'seeTag.php';

/** Represents a throws tag.
 *
 * @file      classes/throwsTag.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Tags
 */

class throwsTag extends seeTag {

    /** Constructor.
     *
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

    /** Get display name of this tag.
     * @return string 'Throws'
     */
    public function displayName() {
        return 'Throws';
    }

    /** Get value of this tag.
     * 
     * @param Doclet doclet
     * @return string
     */
    public function text($doclet) {
        return $this->_linkText($this->_link, $doclet).($this->_text ? ' + '.$this->_text : '');
    }

    /** Return true if this Taglet is used in constructor documentation.
     * @return boolean TRUE
     */
    public function inConstructor() {
        return TRUE;
    }

    /** Return true if this Taglet is used in field documentation.
     * @return boolean FALSE
     */
    public function inField() {
        return FALSE;
    }

    /** Return true if this Taglet is used in method documentation.
     * @return boolean TRUE
     */
    public function inMethod() {
        return TRUE;
    }

    /** Return true if this Taglet is used in overview documentation.
     * @return boolean FALSE
     */
    public function inOverview() {
        return FALSE;
    }

    /** Return true if this Taglet is used in package documentation.
     * @return boolean FALSE
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
}
