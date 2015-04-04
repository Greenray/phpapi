<?php
/** Represents a parameter tag.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/paramTag.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Tags
 */

class paramTag extends tag {

    /** The variable name of the parameter.
     * @var string
     */
    public $var = NULL;

    /** Constructor.
     * @param  string  $text The contents of the tag
     * @param  array   $data Reference to doc comment data array
     * @param  rootDoc $root The root object
     * @return void
     */
    public function paramTag($text, &$data, &$root) {
        $explode = preg_split('/[ \t]+/', $text);
        $type = array_shift($explode);
        if ($type) {
            $this->var = trim(array_shift($explode), '$');
            if ($this->var) {
                $data['parameters'][$this->var]['type'] = $type;
            } else {
                $count = isset($data['parameters']) ? count($data['parameters']) : 0;
                $data['parameters']['__unknown'.$count]['type'] = $type;
            }
            $text = join(' ', $explode);
        }
        if ($text !== '') {
               parent::tag('@param', '$'.$this->var.'+'.$text, $root, $type);
        } else parent::tag('@param', NULL, $root);
    }

    /** Gets display name of this tag.
     * @return string The word "Parameters"
     */
    public function displayName() {
        return 'Parameters';
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
