<?php
/** Base class of all Doc classes.
 * Doc item's are representations of PHP language constructs (class, package, method,...)
 * which have comments and have been processed by this run of phpapi.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/doc.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class doc {

    /** Data about the element creamed from the token stream before the object for this element was created.
     * This array contains extra data about the element that occurs before the element definition in the token
     * stream (including doc comment data), it is merged with the objects fields upon object completion.
     * @var mixed
     */
    public $data = NULL; # This must be NULL so set does not nest the arrays when $currentData is assigned

    /** @ var string Description of the package */
    public $desc;

    /** @var string The unprocessed doc comment */
    public $docComment = '';

    /** @var integer Whether parsing is inside this elements curly braces */
    public $inBody = 0;

    /** @var string The name of this construct */
    public $name = NULL;

    /** Detailed package description */
    public $overview;

    /** @var rootDoc The reference the root element */
    public $root = NULL;

    /** @var tag Array of doc tags */
    public $tags = [];

    /** Constructor. */
    public function __construct() {}

    /** Setter method.
     * @param  string  $member Name of the member to set
     * @param  mixed   $value  The value to set member to
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
     * @param  string  $member Name of the member to set
     * @param  mixed   &$value The reference to the value of the variable, constant, etc.
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

    /** Constructs a class.
     * False until overridden.
     * @return boolean
     */
    public function isClass() {
        return FALSE;
    }

    /** Constructs an ordinary class (not an interface or an exception).
     * False until overridden.
     * @return boolean
     */
    public function isOrdinaryClass() {
        return FALSE;
    }

    /** Constructs a constructor.
     * False until overridden.
     * @return boolean
     */
    public function isConstructor() {
        return FALSE;
    }

    /** Constructs an exception.
     * False until overridden.
     * @return boolean
     */
    public function isException() {
        return FALSE;
    }

    /** Constructs a global variable.
     * False until overridden.
     * @return boolean
     */
    public function isGlobal() {
        return FALSE;
    }

    /** Constructs a field.
     * False until overridden.
     * @return boolean
     */
    public function isField() {
        return FALSE;
    }

    /** Constructs a function.
     * False until overridden.
     * @return boolean
     */
    public function isFunction() {
        return FALSE;
    }

    /** Constructs an interface.
     * False until overridden.
     * @return boolean
     */
    public function isInterface() {
        return FALSE;
    }

    /** Constructs a method.
     * False until overridden.
     * @return boolean
     */
    public function isMethod() {
        return FALSE;
    }

    /** Constructs an trait.
     * False until overridden.
     * @return boolean
     */
    public function isTrait() {
        return FALSE;
    }

    /** Merges the contents of the doc comment into the element object. */
    public function mergeData() {
        if (!empty($this->data) && is_array($this->data)) {

            # Merge primitive types
            foreach ($this->data as $member => $value) {
                if (!is_array($value)) {
                    if ($member === 'type')
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
                            if (($thisClass === 'rootDoc'    && $this->data['tags'][$name][$key]->inOverview()) ||
                                ($thisClass === 'packageDoc' && $this->data['tags'][$name][$key]->inPackage())  ||
                                ($thisClass === 'classDoc'   && $this->data['tags'][$name][$key]->inType())     ||
                                ($thisClass === 'methodDoc'  && $this->data['tags'][$name][$key]->inMethod())   ||
                                ($thisClass === 'fieldDoc'   && $this->data['tags'][$name][$key]->inField()))
                            {
                                $this->tags[$name][$key] = &$this->data['tags'][$name][$key];
                                $this->tags[$name][$key]->setParent($this);
                            }
                        }
                    } else {
                        if (($thisClass === 'rootDoc'    && $this->data['tags'][$name]->inOverview()) ||
                            ($thisClass === 'packageDoc' && $this->data['tags'][$name]->inPackage())  ||
                            ($thisClass === 'classDoc'   && $this->data['tags'][$name]->inType())     ||
                            ($thisClass === 'methodDoc'  && $this->data['tags'][$name]->inMethod())   ||
                            ($thisClass === 'fieldDoc'   && $this->data['tags'][$name]->inField()))
                        {
                            $this->tags[$name] = &$this->data['tags'][$name];
                            $this->tags[$name]->setParent($this);
                        }
                    }
                }
            }

            # Merge parameter types
            if (isset($this->parameters) && isset($this->data['parameters'])) {
                foreach ($this->data['parameters'] as $name => $param) {
                    if (substr($name, 0, 9) === '__unknown') {
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
