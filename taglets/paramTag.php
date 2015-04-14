<?php
/** Represents a parameter tag.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      taglets/paramTag.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   tags
 */

class paramTag extends tag {

    /** @var string The variable name of the parameter */
    public $var = NULL;

    /** Constructor.
     * @param  string  $text  The contents of the tag
     * @param  array   &$data The reference to the doc comment data array
     * @param  rootDoc &$root The reference to the root object
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

    /** DisplayS "Parameters"- the name of this tag.
     * @return string
     */
    public function displayName() {
        return 'Parameters';
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
