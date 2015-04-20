<?php
/**
 * Represents a see tag.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      taglets/seeTag.php
 * @version   4.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   tags
 */

class seeTag extends tag {

    /** @var string Link */
    public $link = NULL;

    /**
     * Constructor.
     *
     * @param string  $text  Contents of the tag
     * @param array   &$data Reference to the doc comment data array
     * @param rootDoc &$root Reference to the root object
     */
    public function __construct($text, &$data, &$root) {
        if (preg_match('/^<a href="(.+)">(.+)<\/a>$/', $text, $matches)) {
            $this->link = $matches[1];
            $text = $matches[2];
        } elseif (preg_match('/^([^ ]+)([ \t](.*))?$/', $text, $matches)) {
            $this->link = $matches[1];
            if (isset($matches[3])) $text = $matches[3];
        } else $this->link = NULL;
        parent::tag('@see', $text, $root);
    }

    /**
     * Gets display name of this tag.
     *
     * @return string "See also"
     */
    public function displayName() {
        return 'See also';
    }

    /**
     * Generate the text to go into the seeTag link.
     *
     * @param  string $link Link to process
     * @return string
     */
    public function linkText($link) {
        $element = &$this->resolveLink();
        if ($element && $this->parent) {
            $package = &$this->parent->containingPackage();
            $path    = str_repeat('../', $package->depth() + 1).$element->asPath();
            return '<a href="'.$path.'">'.$link.'</a>';

        } elseif (preg_match('/^(https?|ftp):\/\//', $this->link) === 1)
             return '<a href="'.$this->link.'">'.$link.'</a>';
        else return $link;
    }

    /**
     * Turn the objects link text into a link to the element it refers to.
     *
     * @return elementDoc
     */
    function &resolveLink() {
        $matches = [];
        $return  = NULL;
        $packageRegex = '[a-zA-Z0-9_\x7f-\xff .\\\\-]+';
        $labelRegex   = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
        $regex        = '/^\\\\?(?:('.$packageRegex.')[.\\\\])?(?:('.$labelRegex.')(?:#|::))?\$?('.$labelRegex.')(?:\(\))?$/';
        if (preg_match($regex, $this->link, $matches)) {
            $packageName = $matches[1];
            $className   = $matches[2];
            $elementName = $matches[3];

            if ($packageName) {
                $package = &$this->root->packageNamed($packageName);
                if (!$package) {
                    return $return;
                }
            }

            if ($className) {
                if (isset($package))
                     $classes = &$package->classes;
                else $classes = &$this->root->classes();
                if ($classes) {
                    foreach ($classes as $key => $class) {
                        if ($class->name === $className) {
                            break;
                        }
                    }
                    $class = &$classes[$key];
                }
            }

            if ($elementName) {

                if (isset($class)) {
                    $methods = &$class->methods();
                    if ($methods) {
                        foreach ($methods as $key => $method) {
                            if ($method->name === $elementName) {
                                $element = &$methods[$key];
                                break;
                            }
                        }
                    }
                    if (!isset($element)) {
                        if ($class->fields) {
                            foreach ($class->fields as $key => $field) {
                                if ($field->name === $elementName) {
                                    $element = &$field;
                                    break;
                                }
                            }
                        }
                    }
                } elseif (isset($package)) {
                    $classes = &$package->classes;
                    foreach ($classes as $key => $class) {
                        if ($class->name === $elementName) {
                            $element = &$classes[$key];
                            break;
                        }
                        $methods = &$class->methods();
                        if ($methods) {
                            foreach ($methods as $key => $method) {
                                if ($method->name === $elementName) {
                                    $element = &$methods[$key];
                                    break 2;
                                }
                            }
                        }
                        if (!isset($element)) {
                            if ($class->fields) {
                                foreach ($class->fields as $key => $field) {
                                    if ($field->name === $elementName) {
                                        $element = &$field;
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($element)) {
                        $functions = &$package->functions;
                        if ($functions) {
                            foreach ($functions as $key => $function) {
                                if ($function->name === $elementName) {
                                    $element = &$functions[$key];
                                    break;
                                }
                            }
                        }
                        if (!isset($element)) {
                            $globals = &$package->globals;
                            if ($globals) {
                                foreach ($globals as $key => $global) {
                                    if ($global->name === $elementName) {
                                        $element = &$globals[$key];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                #
                # From anywhere
                #
                } else {
                    $classes = &$this->root->classes();
                    if ($classes) {
                        foreach ($classes as $key => $class) {
                            if ($class->name === $elementName) {
                                $element = &$classes[$key];
                                break;
                            }
                            $methods = &$class->methods();
                            if ($methods) {
                                foreach ($methods as $key => $method) {
                                    if ($method->name === $elementName) {
                                        $element = &$methods[$key];
                                        break 2;
                                    }
                                }
                            }
                            if (!isset($element)) {
                                if ($class->fields) {
                                    foreach ($class->fields as $key => $field) {
                                        if ($field->name === $elementName) {
                                            $element = &$field;
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
                                if ($function->name === $elementName) {
                                    $element = &$functions[$key];
                                    break;
                                }
                            }
                        }
                        if (!isset($element)) {
                            $globals = &$this->root->globals();
                            if ($globals) {
                                foreach ($globals as $key => $global) {
                                    if ($global->name === $elementName) {
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

    /**
     * Returns TRUE if this Taglet is used in constructor documentation.
     *
     * @return boolean
     */
    public function inConstructor() {
        return TRUE;
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
