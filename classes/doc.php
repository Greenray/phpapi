<?php
/** Abstract base class of all Doc classes.
 * Doc item's are representations of PHP language constructs (class, package, method,...)
 * which have comments and have been processed by this run of phpapi.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/doc.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 * @abstract
 */

class doc {

    /** Data about the element creamed from the token stream before the object for this element was created.
     * This array contains extra data about the element that occurs before the element definition in the token
     * stream (including doc comment data), it is merged with the objects fields upon object completion.
     * @var mixed[]
     */
    public $data = NULL; # This must be NULL so set does not nest the arrays when $currentData is assigned

    /** The unprocessed doc comment.
     * @var string
     */
    public $docComment = '';

    /** Whether parsing is inside this elements curly braces.
     * @var integer
     */
    public $inBody = 0;

    /** The name of this construct.
     * @var string
     */
    public $name = NULL;

    /** Reference to the root element.
     * @var rootDoc
     */
    public $root = NULL;

    /** Array of doc tags.
     * @var tag[]
     */
    public $tags = [];

    /** Setter method.
     *
     * @param  string $member Name of the member to set
     * @param  mixed  $value  The value to set member to
     * @return boolean
     */
    public function set($member, $value) {
        $members = get_object_vars($this);
        if (array_key_exists($member, $members)) {
            if (is_array($this->$member))
                 $this->{$member}[] = $value;
            else $this->$member     = $value;
            return TRUE;
        }
        return FALSE;
    }

    /** Setter by reference method.
     *
     * @param  string $member Name of the member to set
     * @param  mixed  $value  The value to set member to
     * @return boolean
     */
    public function setByRef($member, &$value) {
        $members = get_object_vars($this);
        if (array_key_exists($member, $members)) {
            if (is_array($this->$member))
                 $this->{$member}[] =& $value;
            else $this->$member     =& $value;
            return TRUE;
        }
        return FALSE;
    }

    /** Returns the name of this doc item.
     *
     * @return string
     */
    public function name() {
        return $this->name;
    }

    /** Returns tags of the specified kind in this Doc item.
     * For example, if 'tagName' has value "@serial", all tags in this Doc item of type "@serial" will be returned.
     * If NULL is given for 'tagName', all tags in this Doc item are returned.
     *
     * @param  string     $tagName Name of the tag kind to search for
     * @return Tag[]|NULL          An array of Tag containing all tags of name 'tagname' or a
     *                             singular tag object if only one exists for the given 'tagname'
     */
    function &tags($tagName = NULL) {
        $return = NULL;
        if     ($tagName == NULL)             $return = &$this->tags;
        elseif (isset($this->tags[$tagName])) $return = &$this->tags[$tagName];
        return $return;
    }

    /** Sets a tag.
     *
     * @param string $tagName Name of the tag kind to search for
     * @param Tag    $tag     The tag to set
     */
    public function setTag($tagName, $tag) {
        $this->tags[$tagName] = &$tag;
    }

    /** Constructs a class.
     * @return boolean False until overridden
     */
    public function isClass() {
        return FALSE;
    }

    /** Constructs a constructor.
     * @return boolean False until overridden
     */
    public function isConstructor() {
        return FALSE;
    }

    /** Constructs an exception.
     * @return boolean False until overridden
     */
    public function isException() {
        return FALSE;
    }

    /** Constructs a global variable.
     * @return boolean False until overridden
     */
    public function isGlobal() {
        return FALSE;
    }

    /** Constructs final.
     * @return boolean False until overridden
     */
    public function isFinal() {
        return FALSE;
    }

    /** Constructs a field.
     * @return boolean False until overridden
     */
    public function isField() {
        return FALSE;
    }

    /** Constructs a function.
     * @return boolean False until overridden
     */
    public function isFunction() {
        return FALSE;
    }

    /** Constructs an interface.
     * @return boolean False until overridden
     */
    public function isInterface() {
        return FALSE;
    }

    /** Constructs an trait.
     * @return boolean False until overridden
     */
    public function isTrait() {
        return FALSE;
    }

    /** Constructs a method.
     * @return boolean False until overridden
     */
    public function isMethod() {
        return FALSE;
    }

    /** Constructs an ordinary class (not an interface or an exception).
     * @return boolean False until overridden
     */
    public function isOrdinaryClass() {
        return FALSE;
    }

    /** Merges the contents of the doc comment into the element object.
     *
     * @return void
     */
    public function mergeData() {
        if (isset($this->data) && is_array($this->data)) {

            # Merge primitive types
            foreach ($this->data as $member => $value) {
                if (!is_array($value)) {
                    if ($member == 'type')
                         $this->set('type', new type($value, $this->root));
                    else $this->set($member, $value);
                }
            }

            # Merge tags array
            if (isset($this->data['tags']) && is_array($this->data['tags'])) {
                $thisClass = get_class($this);
                foreach ($this->data['tags'] as $name => $tag) {
                    if (is_array($tag)) {
                        foreach ($this->data['tags'][$name] as $key => $tag) {
                            if (
                                ($thisClass == 'rootDoc'    && $this->data['tags'][$name][$key]->inOverview()) ||
                                ($thisClass == 'packageDoc' && $this->data['tags'][$name][$key]->inPackage())  ||
                                ($thisClass == 'classDoc'   && $this->data['tags'][$name][$key]->inType())     ||
                                ($thisClass == 'methodDoc'  && $this->data['tags'][$name][$key]->inMethod())   ||
                                ($thisClass == 'fieldDoc'   && $this->data['tags'][$name][$key]->inField())
                            ) {
                                $this->tags[$name][$key] = &$this->data['tags'][$name][$key];
                                $this->tags[$name][$key]->setParent($this);
                            }
                        }
                    } else {
                        if (
                            ($thisClass == 'rootDoc'    && $this->data['tags'][$name]->inOverview()) ||
                            ($thisClass == 'packageDoc' && $this->data['tags'][$name]->inPackage())  ||
                            ($thisClass == 'classDoc'   && $this->data['tags'][$name]->inType())     ||
                            ($thisClass == 'methodDoc'  && $this->data['tags'][$name]->inMethod())   ||
                            ($thisClass == 'fieldDoc'   && $this->data['tags'][$name]->inField())
                        ) {
                            $this->tags[$name] = &$this->data['tags'][$name];
                            $this->tags[$name]->setParent($this);
                        }
                    }
                }
            }

            # Merge parameter types
            if (isset($this->parameters) && isset($this->data['parameters'])) {
                foreach ($this->data['parameters'] as $name => $param) {
                    if (substr($name, 0, 9) == '__unknown') {
                        $index = substr($name, 9);
                        $parameters = array_values($this->parameters);
                        if (isset($parameters[$index])) {
                            $parameters[$index]->set('type', new type($param['type'], $this->root));
                        }
                    } else {
                        if (!isset($this->parameters[$name])) {
                            $this->parameters[$name] = &new fieldDoc($name, $this, $this->root);
                            if (isset($this->package)) {
                                $this->parameters[$name]->set('package', $this->package);
                            }
                        }
                        $this->parameters[$name]->set('type', new type($param['type'], $this->root));
                    }
                }
            }

            # Merge return type
            if (isset($this->returnType) && isset($this->data['return'])) $this->returnType = &new type($this->data['return'], $this->root);

            # Merge exceptions
            if (isset($this->throws) && isset($this->data['throws'])) {
                foreach ($this->data['throws'] as $name => $exception) {
                    $this->throws[$name] = &$this->data['throws'][$name];
                }
            }
        }
    }
}
