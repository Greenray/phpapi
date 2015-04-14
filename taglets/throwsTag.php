<?php
require_once 'seeTag.php';

/** Represents a throws tag.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      taglets/throwsTag.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   tags
 */

class throwsTag extends seeTag {

    /** Constructor.
     * @param  string  $text  The contents of the tag
     * @param  array   &$data The reference to the doc comment data array
     * @param  rootDoc &$root The reference to the root object
     */
    public function __construct($text, &$data, &$root) {
        $explode     = preg_split('/[ \t]+/', $text);
        $this->link = array_shift($explode);
        $data['throws'][$this->link] = $this->link;
        parent::tag('@throws', join(' ', $explode), $root);
    }

    /** Gets display name of this tag.
     * @return string 'Throws'
     */
    public function displayName() {
        return 'Throws';
    }

    /** Returns FALSE if this Taglet is used in field documentation.
     * @return boolean
     */
    public function inField() {
        return FALSE;
    }

    /** Returns TRUE if this Taglet is used in method documentation.
     * @return boolean
     */
    public function inMethod() {
        return TRUE;
    }

    /** Returns FALSE if this Taglet is used in overview documentation.
     * @return boolean
     */
    public function inOverview() {
        return FALSE;
    }

    /** Returns FALSE if this Taglet is used in package documentation.
     * @return boolean
     */
    public function inPackage() {
        return FALSE;
    }

    /** Returns FALSE if this Taglet is used in class or interface documentation.
     * @return boolean
     */
    public function inType() {
        return FALSE;
    }
}
