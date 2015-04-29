<?php
/**
 * Represents an inline link tag.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      taglets/linkTag.php
 * @package   tags
 */

require_once 'linkPlainTag.php';

class linkTag extends linkPlainTag {

    /**
     * Constructor.
     *
     * @param string  $text  Contents of the tag
     * @param array   &$data Reference to the doc comment data array
     * @param rootDoc &$root Reference to the root object
     */
    public function __construct($text, &$data, &$root) {
        parent::linkPlainTag($text, $data, $root);
        $this->name = '@link';
    }

    /**
     * Returns TRUE if this Taglet is used in field documentation.
     *
     * @return boolean
     */
    public function inField() {
        return TRUE;
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
     * Returns TRUE if this Taglet is used in overview documentation.
     *
     * @return boolean
     */
    public function inOverview() {
        return TRUE;
    }

    /**
     * Returns TRUE if this Taglet is used in package documentation.
     *
     * @return boolean
     */
    public function inPackage() {
        return TRUE;
    }

    /**
     * Returns TRUE if this Taglet is used in class or interface documentation.
     *
     * @return boolean
     */
    public function inType() {
        return TRUE;
    }
}
