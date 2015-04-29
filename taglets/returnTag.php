<?php
/**
 * Represents a return tag.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      taglets/returnTag.php
 * @package   tags
 */

class returnTag extends tag {

    /**
     * Constructor.
     *
     * @param string  $text  Contents of the tag
     * @param array   &$data Reference to the doc comment data array
     * @param rootDoc &$root Reference to the root object
     */
    public function __construct($text, &$data, &$root) {
        $explode        = preg_split('/[ \t]+/', $text);
        $data['return'] = array_shift($explode);
        parent::tag('@return', join(' ', $explode), $root, $data['return']);
    }

    /**
     * Displays the name of this tag.
     *
     * @return string "Return"
     */
    public function displayName() {
        return 'Return';
    }

    /**
     * Returns FALSE if this Taglet is not used in field documentation.
     *
     * @return boolean
     */
    public function inField() {
        return FALSE;
    }

    /**
     * Returns TRUE if this Taglet is used in method documentation.
     *
     * @return boolean
     */
    public function inMethod() {
        return TRUE;
    }

    /**
     * Returns FALSE if this Taglet is not used in overview documentation.
     *
     * @return boolean
     */
    public function inOverview() {
        return FALSE;
    }

    /**
     * Returns FALSE if this Taglet is not used in package documentation.
     *
     * @return boolean
     */
    public function inPackage() {
        return FALSE;
    }

    /**
     * Returns FALSE if this Taglet is not used in class or interface documentation.
     *
     * @return boolean
     */
    public function inType() {
        return FALSE;
    }
}
