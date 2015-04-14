<?php
/** Represents a return tag.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      taglets/returnTag.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   tags
 */

class returnTag extends tag {

    /** Constructor.
     * @param  string  $text  The contents of the tag
     * @param  array   &$data The reference to the doc comment data array
     * @param  rootDoc &$root The reference to the root object
     */
    public function __construct($text, &$data, &$root) {
        $explode        = preg_split('/[ \t]+/', $text);
        $data['return'] = array_shift($explode);
        parent::tag('@return', join(' ', $explode), $root, $data['return']);
    }

    /** Displays "Return - the name of this tag.
     * @return string
     */
    public function displayName() {
        return 'Return';
    }

    /** Returns FALSE if this Taglet is not used in field documentation.
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

    /** Returns FALSE if this Taglet is not used in overview documentation.
     * @return boolean
     */
    public function inOverview() {
        return FALSE;
    }

    /** Returns FALSE if this Taglet is not used in package documentation.
     * @return boolean
     */
    public function inPackage() {
        return FALSE;
    }

    /** Returns FALSE if this Taglet is not used in class or interface documentation.
     * @return boolean
     */
    public function inType() {
        return FALSE;
    }
}
