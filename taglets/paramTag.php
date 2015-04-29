<?php
/**
 * Represents a parameter tag.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      taglets/paramTag.php
 * @package   tags
 */

class paramTag extends tag {

    /** @var string Variable name of the parameter */
    public $var = NULL;

    /**
     * Constructor.
     *
     * @param string  $text  Contents of the tag
     * @param array   &$data Reference to the doc comment data array
     * @param rootDoc &$root Reference to the root object
     */
    public function __construct($text, &$data, &$root) {
        $explode = preg_split('/[ \t]+/', $text);
        $type = array_shift($explode);
        if ($type) {
            $this->var = array_shift($explode);
            if ($this->var) {
                $data['parameters'][$this->var]['type'] = $type;
            } else {
                $count = isset($data['parameters']) ? count($data['parameters']) : 0;
                $data['parameters']['__unknown'.$count]['type'] = $type;
            }
            $text = join(' ', $explode);
        }
        if ($text !== '') {
               parent::tag('@param', $this->var.'+'.$text, $root, $type);
        } else parent::tag('@param', NULL, $root);
    }

    /**
     * Displays the name of this tag.
     *
     * @return string "Parameters"
     */
    public function displayName() {
        return 'Parameters';
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
