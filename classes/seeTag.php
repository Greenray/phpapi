<?php
/** Represents a see tag.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/seeTag.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Tags
 */

class seeTag extends tag {

    /** The link.
     * @var string
     */
    public $link = NULL;

    /** Constructor.
     *
     * @param string text The contents of the tag
     * @param array data Reference to doc comment data array
     * @param rootDoc root The root object
     */
    public function seeTag($text, &$data, &$root) {
        if (preg_match('/^<a href="(.+)">(.+)<\/a>$/', $text, $matches)) {
            $this->link = $matches[1];
            $text = $matches[2];
        } elseif (preg_match('/^([^ ]+)([ \t](.*))?$/', $text, $matches)) {
            $this->link = $matches[1];
            if (isset($matches[3])) $text = $matches[3];
        } else $this->link = NULL;
        parent::tag('@see', $text, $root);
    }

    /** Gets display name of this tag.
     * @return string "See also"
     */
    public function displayName() {
        return 'See also';
    }

    /** Gets value of this tag.
     *
     * @param Doclet doclet
     * @return str
     */
    public function text($doclet) {
        $link = parent::text($doclet);
        if (!$link || $link == LF) $link = $this->link;

        return $this->linkText($link, $doclet);
    }

    /** Generate the text to go into the seeTag link.
     *
     * @param  string $link
     * @param  Doclet $doclet
     * @return string
     */
    public function linkText($link, $doclet) {
        $element = &$this->resolveLink();
        if ($element && $this->parent) {
            $package = &$this->parent->containingPackage();
            $path    = str_repeat('../', $package->depth() + 1).$element->asPath();
            return $doclet->formatLink($path, $link);
        } elseif (preg_match('/^(https?|ftp):\/\//', $this->link) === 1)
             return $doclet->formatLink($this->link, $link);
        else return $link;
    }

    /** Turn the objects link text into a link to the element it refers to.
     *
     * @return elementDoc
     */
    function &resolveLink() {
        $phpapi = $this->root->phpapi();
        $matches = [];
        $return = NULL;
        $packageRegex = '[a-zA-Z0-9_\x7f-\xff .\\\\-]+';
        $labelRegex   = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
        $regex = '/^\\\\?(?:('.$packageRegex.')[.\\\\])?(?:('.$labelRegex.')(?:#|::))?\$?('.$labelRegex.')(?:\(\))?$/';
        if (preg_match($regex, $this->link, $matches)) {
            $packageName = $matches[1];
            $className   = $matches[2];
            $elementName = $matches[3];
            if ($packageName) { # Get package
                $package = &$this->root->packageNamed($packageName);
                if (!$package) {
                    return $return;
                }
            }
            if ($className) { # Get class
                if (isset($package))
                     $classes = &$package->allClasses();
                else $classes = &$this->root->classes();
                if ($classes) {
                    foreach ($classes as $key => $class) {
                        if ($class->name() == $className) {
                            break;
                        }
                    }
                    $class = &$classes[$key];
                }
            }
            if ($elementName) {      # Get element
                if (isset($class)) { # From class
                    $methods = &$class->methods();
                    if ($methods) {
                        foreach ($methods as $key => $method) {
                            if ($method->name() == $elementName) {
                                $element = &$methods[$key];
                                break;
                            }
                        }
                    }
                    if (!isset($element)) {
                        $fields = &$class->fields();
                        if ($fields) {
                            foreach ($fields as $key => $field) {
                                if ($field->name() == $elementName) {
                                    $element = &$fields[$key];
                                    break;
                                }
                            }
                        }
                    }
                } elseif (isset($package)) { # From package
                    $classes = &$package->allClasses();
                    foreach ($classes as $key => $class) {
                        if ($class->name() == $elementName) {
                            $element = &$classes[$key];
                            break;
                        }
                        $methods = &$class->methods();
                        if ($methods) {
                            foreach ($methods as $key => $method) {
                                if ($method->name() == $elementName) {
                                    $element = &$methods[$key];
                                    break 2;
                                }
                            }
                        }
                        if (!isset($element)) {
                            $fields = &$class->fields();
                            if ($fields) {
                                foreach ($fields as $key => $field) {
                                    if ($field->name() == $elementName) {
                                        $element = &$fields[$key];
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($element)) {
                        $functions = &$package->functions();
                        if ($functions) {
                            foreach ($functions as $key => $function) {
                                if ($function->name() == $elementName) {
                                    $element = &$functions[$key];
                                    break;
                                }
                            }
                        }
                        if (!isset($element)) {
                            $globals = &$package->globals();
                            if ($globals) {
                                foreach ($globals as $key => $global) {
                                    if ($global->name() == $elementName) {
                                        $element = &$globals[$key];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                } else { # From anywhere
                    $classes = &$this->root->classes();
                    if ($classes) {
                        foreach ($classes as $key => $class) {
                            if ($class->name() == $elementName) {
                                $element = &$classes[$key];
                                break;
                            }
                            $methods = &$class->methods();
                            if ($methods) {
                                foreach ($methods as $key => $method) {
                                    if ($method->name() == $elementName) {
                                        $element = &$methods[$key];
                                        break 2;
                                    }
                                }
                            }
                            if (!isset($element)) {
                                $fields = &$class->fields();
                                if ($fields) {
                                    foreach ($fields as $key => $field) {
                                        if ($field->name() == $elementName) {
                                            $element = &$fields[$key];
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($element)) {
                        $functions = &$this->root->functions();
                        if ($functions) {
                            foreach ($functions as $key => $function) {
                                if ($function->name() == $elementName) {
                                    $element = &$functions[$key];
                                    break;
                                }
                            }
                        }
                        if (!isset($element)) {
                            $globals = &$this->root->globals();
                            if ($globals) {
                                foreach ($globals as $key => $global) {
                                    if ($global->name() == $elementName) {
                                        $element = &$globals[$key];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $return = &$element;
        }
        return $return;
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
        return FALSE;
    }
}
