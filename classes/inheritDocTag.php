<?php
# phpapi: The PHP Documentation Creator

/** Represents a see tag.
 *
 * @file      classes/inheritDocTag.php
 * @version   2.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Tags
 */

class inheritDocTag extends tag {

    /** Constructor.
     * @param  string  $text The contents of the tag
     * @param  array   $data Reference to doc comment data array
     * @param  rootDoc $root The root object
     * @return void
     */
    public function inheritDocTag($text, &$data, &$root) {
        parent::tag('@inheritDoc', $text, $root);
    }

    /** Gets text from super element
     * @param TextFormatter formatter
     * @return str
     */
    public function text($formatter) {
        if ($this->_parent) {
            if ($this->_parent->isClass()) {
                $superClassname = $this->_parent->superclass();
                if ($superClassname) {
                    $superClass =& $this->_root->classNamed($superClassname);
                    if ($superClass) {
                        $textTag = $superClass->tags('@text');
                        if ($textTag) {
                            $text = $textTag->text($formatter);
                            if ($text) {
                                return $text;
                            }
                        }
                    }
                }
                $interfaces = $this->_parent->interfaces();
                foreach ($interfaces as $interface) {
                    $textTag = $interface->tags('@text');
                    if ($textTag) {
                        $text = $textTag->text($formatter);
                        if ($text) {
                            return $text;
                        }
                    }
                }
            } elseif ($this->_parent->isConstructor() || $this->_parent->isMethod()) {
                $parentClass =& $this->_parent->containingClass();
                if ($parentClass) {
                    $superClassname = $parentClass->superclass();
                    if ($superClassname) {
                        $superClass =& $this->_root->classNamed($superClassname);
                        if ($superClass) {
                            $superMethod =& $superClass->methodNamed($this->_parent->name());
                            if ($superMethod) {
                                $textTag = $superMethod->tags('@text');
                                if ($textTag) {
                                    $text = $textTag->text($formatter);
                                    if ($text) {
                                        return $text;
                                    }
                                }
                            }
                        }
                    }
                    $interfaces = $parentClass->interfaces();
                    foreach ($interfaces as $interface) {
                        $superMethod =& $interface->methodNamed($this->_parent->name());
                        if ($superMethod) {
                            $textTag = $superMethod->tags('@text');
                            if ($textTag) {
                                $text = $textTag->text($formatter);
                                if ($text) {
                                    return $text;
                                }
                            }
                        }
                    }
                }
            } elseif ($this->_parent->isField()) {
                $parentClass =& $this->_parent->containingClass();
                if ($parentClass) {
                    $superClassname = $parentClass->superclass();
                    if ($superClassname) {
                        $superClass =& $this->_root->classNamed($superClassname);
                        if ($superClass) {
                            $superField =& $superClass->fieldNamed($this->_parent->name());
                            if ($superField) {
                                $textTag = $superField->tags('@text');
                                if ($textTag) {
                                    $text = $textTag->text($formatter);
                                    if ($text) {
                                        return $text;
                                    }
                                }
                            }
                        }
                    }
                    $interfaces = $parentClass->interfaces();
                    foreach ($interfaces as $interface) {
                        $superField =& $interface->fieldNamed($this->_parent->name());
                        if ($superField) {
                            $textTag = $superField->tags('@text');
                            if ($textTag) {
                                $text = $textTag->text($formatter);
                                if ($text) {
                                    return $text;
                                }
                            }
                        }
                    }
                }
            }
        }
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
