<?php
# phpAPI: The PHP Documentation Creator

require_once 'seeTag.php';

/** Represents an inline link tag.
 * @file      classes/linkPlainTag.php
 * @version   1.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Tags
 */

class linkPlainTag extends SeeTag {

    /** Constructor.
     * @param str text The contents of the tag
     * @param array data Reference to doc comment data array
     * @param RootDoc root The root object
     */
    public function linkPlainTag($text, &$data, &$root) {
        $explode = preg_split('/[ \t]+/', $text);
        $link = array_shift($explode);
        if ($link) {
            $this->_link = $link;
            $text = join(' ', $explode);
        } else {
            $this->_link = NULL;
        }
        parent::tag('@linkplain', $text, $root);
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
        return TRUE;
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
        return TRUE;
    }

    /** Return true if this Taglet is used in package documentation.
     * @return bool
     */
    public function inPackage() {
        return TRUE;
    }

    /** Return true if this Taglet is used in class or interface documentation.
     * @return bool
     */
    public function inType() {
        return TRUE;
    }

    /** Return true if this Taglet is an inline tag.
     * @return bool
     */
    public function isInlineTag() {
        return TRUE;
    }
}
