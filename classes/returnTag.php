<?php
/** Represents a return tag.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/returnTag.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Tags
 */

class returnTag extends tag {

    /** Constructor.
     *
     * @param  string  $text The contents of the tag
     * @param  array   $data Reference to doc comment data array
     * @param  rootDoc $root The root object
     * @return void
     */
    public function returnTag($text, &$data, &$root) {
        $explode        = preg_split('/[ \t]+/', $text);
        $data['return'] = array_shift($explode);
        parent::tag('@return', join(' ', $explode), $root, $data['return']);
    }

    /** Gets display name of this tag.
     *
     * @return str
     */
    public function displayName() {
        return 'Return';
    }

    /** Returns true if this Taglet is used in constructor documentation.
     * @return bool
     */
    public function inConstructor() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in field documentation.
     * @return bool
     */
    public function inField() {
        return FALSE;
    }

    /** Returns true if this Taglet is used in method documentation.
     * @return bool
     */
    public function inMethod() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in overview documentation.
     * @return bool
     */
    public function inOverview() {
        return FALSE;
    }

    /** Returns true if this Taglet is used in package documentation.
     * @return bool
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

    /** Returns true if this Taglet should be outputted even if it has no text content.
     * @return bool
     */
    public function displayEmpty() {
        return FALSE;
    }
}
