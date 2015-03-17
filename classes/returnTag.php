<?php
# phpapi: The PHP Documentation Creator

/** Represents a return tag.
 *
 * @file      classes/returnTag.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Tags
 */

class returnTag extends Tag {

    /** Constructor.
     * @param string     $text The contents of the tag
     * @param array   $data Reference to doc comment data array
     * @param rootDoc $root The root object
     */
    public function returnTag($text, &$data, &$root) {
        $explode        = preg_split('/[ \t]+/', $text);
        $data['return'] = array_shift($explode);
        parent::tag('@return', join(' ', $explode), $root, $data['return']);
    }

    /** Get display name of this tag.
     * @return str
     */
    public function displayName() {
        return 'Return';
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
