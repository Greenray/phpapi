<?php
require_once 'seeTag.php';

/** Represents an inline link tag.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      taglets/linkPlainTag.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   tags
 */

class linkPlainTag extends seeTag {

    /** Constructor.
     * @param  string  $text  The contents of the tag
     * @param  array   &$data The reference tto he doc comment data array
     * @param  rootDoc &$root The reference to the root object
     */
    public function linkPlainTag($text, &$data, &$root) {
        $explode = preg_split('/[ \t]+/', $text);
        $link = array_shift($explode);
        if ($link) {
            $this->link = $link;
            $text = join(' ', $explode);
        } else {
            $this->link = NULL;
        }
        parent::tag('@linkplain', $text, $root);
    }

    /** Returns TRUE if this Taglet is used in field documentation.
     * @return boolean
     */
    public function inField() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in method documentation.
     * @return boolean
     */
    public function inMethod() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in overview documentation.
     * @return boolean
     */
    public function inOverview() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in package documentation.
     * @return boolean
     */
    public function inPackage() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in class or interface documentation.
     * @return boolean
     */
    public function inType() {
        return TRUE;
    }
}
