<?php
# phpapi: The PHP Documentation Creator

/** Represents a see tag.
 * @file      classes/inheritDocTag.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Tags
 */

class inheritDocTag extends Tag {

    /** Constructor.
     * @param str text The contents of the tag
     * @param array data Reference to doc comment data array
     * @param RootDoc root The root object
     */
    public function inheritDocTag($text, &$data, &$root) {
        parent::tag('@inheritDoc', $text, $root);
    }

    /** Get text from super element
     * @param TextFormatter formatter
     * @return str
     */
    public function text($formatter) {
        if ($this->_parent) {
            if ($this->_parent->isClass()) {
                $superClassname = $this->_parent->superclass();
                if ($superClassname) {
                    $superClass = & $this->_root->classNamed($superClassname);
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
                $parentClass = & $this->_parent->containingClass();
                if ($parentClass) {
                    $superClassname = $parentClass->superclass();
                    if ($superClassname) {
                        $superClass = & $this->_root->classNamed($superClassname);
                        if ($superClass) {
                            $superMethod = & $superClass->methodNamed($this->_parent->name());
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
                        $superMethod = & $interface->methodNamed($this->_parent->name());
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
                $parentClass = & $this->_parent->containingClass();
                if ($parentClass) {
                    $superClassname = $parentClass->superclass();
                    if ($superClassname) {
                        $superClass = & $this->_root->classNamed($superClassname);
                        if ($superClass) {
                            $superField = & $superClass->fieldNamed($this->_parent->name());
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
                        $superField = & $interface->fieldNamed($this->_parent->name());
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

    /** Return true if this Taglet is used in constructor documentation.
     * @return bool
     */
    public function inConstructor() {
        return TRUE;
    }

    /** Return true if this Taglet is used in field documentation.
     * @return bool
     */
    public function inField() {
        return TRUE;
    }

    /** Return true if this Taglet is used in method documentation.
     * @return bool
     */
    public function inMethod() {
        return TRUE;
    }

    /** Return true if this Taglet is used in overview documentation.
     * @return bool
     */
    public function inOverview() {
        return TRUE;
    }

    /** Return true if this Taglet is used in package documentation.
     * @return bool
     */
    public function inPackage() {
        return TRUE;
    }

    /** Return true if this Taglet is used in class or interface documentation.
     * @return bool
     */
    public function inType() {
        return TRUE;
    }

    /** Return true if this Taglet is an inline tag.
     * @return bool
     */
    public function isInlineTag() {
        return TRUE;
    }
}
