<?php
# phpapi: The PHP Documentation Creator

require_once 'linkPlainTag.php';

/** Represents an inline link tag.
 *
 * @file      classes/linkTag.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Tags
 */

class linkTag extends linkPlainTag {

    /** Constructor.
     * @param  string  $text The contents of the tag
     * @param  array   $data Reference to doc comment data array
     * @param  rootDoc $root The root object
     * @return void
     */
    public function linkTag($text, &$data, &$root) {
        parent::linkPlainTag($text, $data, $root);
        $this->_name = '@link';
    }

    /** Gets the value of the tag as raw data, without any text processing applied.
     * @param TextFormatter formatter
     * @return str
     */
    public function text($formatter) {
        return $formatter->asCode(parent::text($formatter));
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
        return TRUE;
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
        return TRUE;
    }

    /** Returns true if this Taglet is used in package documentation.
     * @return bool
     */
    public function inPackage() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in class or interface documentation.
     * @return bool
     */
    public function inType() {
        return TRUE;
    }

    /** Returns true if this Taglet is an inline tag.
     * @return bool
     */
    public function isInlineTag() {
        return TRUE;
    }
}
