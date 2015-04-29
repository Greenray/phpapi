<?php
/**
 * Represents a throws tag.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      taglets/throwsTag.php
 * @package   tags
 */

require_once 'seeTag.php';

class throwsTag extends seeTag {

    /**
     * Constructor.
     *
     * @param string  $text  Contents of the tag
     * @param array   &$data Reference to the doc comment data array
     * @param rootDoc &$root Reference to the root object
     */
    public function __construct($text, &$data, &$root) {
        $explode    = preg_split('/[ \t]+/', $text);
        $this->link = array_shift($explode);
        $data['throws'][$this->link] = $this->link;
        parent::tag('@throws', join(' ', $explode), $root, 'Exception');
    }

    /**
     * Displays the name of this tag.
     *
     * @return string "Throws"
     */
    public function displayName() {
        return 'Throws';
    }

    /**
     * Returns FALSE if this Taglet is used in field documentation.
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
     * Returns FALSE if this Taglet is used in overview documentation.
     *
     * @return boolean
     */
    public function inOverview() {
        return FALSE;
    }

    /**
     * Returns FALSE if this Taglet is used in package documentation.
     *
     * @return boolean
     */
    public function inPackage() {
        return FALSE;
    }

    /**
     * Returns FALSE if this Taglet is used in class or interface documentation.
     *
     * @return boolean
     */
    public function inType() {
        return FALSE;
    }
}
