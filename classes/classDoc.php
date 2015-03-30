<?php
# phpapi: The PHP Documentation Creator

/** Represents a PHP class and provides access to information about the class,
 * class's comment and tags, and the members of the class.
 *
 * @file      classes/classDoc.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class classDoc extends elementDoc {

    /** The super class.
     * @var string
     */
    public $_superclass = NULL;

    /** Is this an interface?
     * @var boolean
     */
    public $_interface = FALSE;

    /** Is this a trait?
     * @var boolean
     */
    public $_trait = FALSE;

    /** The class constants.
     * @var fieldDoc[]
     */
    public $_constants = [];

    /** The class fields.
     * @var fieldDoc[]
     */
    public $_fields = [];

    /** The class methods.
     * @var methodDoc[]
     */
    public $_methods = [];

    /** Interfaces this class implements or this interface extends.
     * @var classDoc[]
     */
    public $_interfaces = [];

    /** Traits this class uses.
     * @var classDoc[]
     */
    public $_traits = [];

    /** Is this class abstract.
     * @var boolean
     */
    public $_abstract = FALSE;

    /** Constructor.
     * @param  string  $name       The name of this element
     * @param  rootDoc $filename   The filename of the source file this element is in
     * @param  integer $lineNumber The line number of the source file this element is at
     * @param  string  $sourcePath The source path containing the source file
     * @return void
     */
    public function classDoc($name, &$root, $filename, $lineNumber, $sourcePath) {
        $this->_name       = $name;
        $this->_root       =& $root;
        $this->_filename   = $filename;
        $this->_lineNumber = $lineNumber;
        $this->_sourcePath = $sourcePath;
    }

    /** Adds a constant to this class.
     * @param  fieldDoc[] $constant Link to a constant
     * @return void
     */
    public function addConstant(&$constant) {
        if (!isset($this->_constants[$constant->name()])) $this->_constants[$constant->name()] =& $constant;
    }

    /** Adds a field to this class.
     * @param  fieldDoc[] $field Link to a field
     * @return void
     */
    public function addField(&$field) {
        if (!isset($this->_fields[$field->name()])) $this->_fields[$field->name()] =& $field;
    }

    /** Adds a method to this class.
     * @param  methodDoc[] $method Link to a method
     * @return void
     */
    public function addMethod(&$method) {
        if (isset($this->_methods[$method->name()])) {
            $phpapi =& $this->_root->phpapi();
            echo LF;
            $phpapi->warning('Found method '.$method->name().' again, overwriting previous version');
        }
        $this->_methods[$method->name()] =& $method;
    }

    /** Returns constants in this class.
     * @return fieldDoc[] List of constants
     */
    public function &constants() {
        return $this->_constants;
    }

    /** Returns fields in this class.
     * @return fieldDoc[] List of fields
     */
    public function &fields() {
        return $this->_fields;
    }

    /** Returns a field in this class.
     * @return fieldDoc[] Field from current class
     */
    public function &fieldNamed($fieldName) {
        $return = NULL;
        if (isset($this->_fields[$fieldName])) $return =& $this->_fields[$fieldName];
        return $return;
    }

    /** Returns the methods in this class.
     * @param  boolean     $regularOnly Do not return constructors and destructors
     * @return methodDoc[]              List of class methods
     */
    public function &methods($regularOnly = FALSE) {
        if ($regularOnly) {
            $return = [];
            foreach ($this->_methods as $method) {
                if (!$method->isConstructor() && !$method->isDestructor()) $return[] = $method;
            }
        } else $return = $this->_methods;
        return $return;
    }

    /** Returns a method in this class.
     * @return methodDoc[] Method from current class
     */
    public function &methodNamed($methodName) {
        $return = NULL;
        if (isset($this->_methods[$methodName])) $return =& $this->_methods[$methodName];
        return $return;
    }

    /** Returns constructor for this class.
     * @return methodDoc[] Constructor
     */
    public function &constructor() {
        $return = NULL;
        foreach ($this->_methods as $method) {
            if ($method->isConstructor()) {
                $return =& $method;
                break;
            }
        }
        return $return;
    }

    /** Returns destructor for this class.
     * @return methodDoc[] Destructor
     */
    public function &destructor() {
        $return = NULL;
        foreach ($this->_methods as $method) {
            if ($method->isDestructor()) {
                $return =& $method;
                break;
            }
        }
        return $return;
    }

    /** Returns interfaces implemented by this class or interfaces extended by this interface.
     * @return classDoc[] List of interfaces
     */
    public function &interfaces() {
        return $this->_interfaces;
    }

    /** Returns an interface in this class.
     * @return classDoc[] Interface from current class
     */
    public function &interfaceNamed($interfaceName) {
        $return = NULL;
        if (isset($this->_interfaces[$interfaceName])) $return =& $this->_interfaces[$interfaceName];
        return $return;
    }

    /** Returns traits used by this class
     * @return classDoc[]
     */
    public function &traits() {
        return $this->_traits;
    }

    /** Returns an trait in this class.
     * @return classDoc[] Trait from current class
     */
    public function &traitNamed($traitName) {
        $return = NULL;
        if (isset($this->_traits[$traitName])) $return =& $this->_traits[$traitName];
        return $return;
    }

    /** Returns true if this class is abstract.
     * @return boolean
     */
    public function isAbstract() {
        return $this->_abstract;
    }

    /** Returns true if this element is an interface.
     * @return boolean
     */
    public function isInterface() {
        return $this->_interface;
    }

    /** Returns true if this element is a trait.
     * @return boolean
     */
    public function isTrait() {
        return $this->_trait;
    }

    /** Tests whether this class is a subclass of the specified class.
     * @param  classDoc $cd Specified class
     * @return boolean      The result of the validation
     */
    public function subclassOf($cd) {
        return ($this->_superclass == $cd->name()) ? TRUE : FALSE;
    }

    /** Returns the superclass of this class.
     * @return classDoc[]
     */
    public function superclass() {
        return $this->_superclass;
    }

    /** Constructs a class.
     * @note interfaces are not classes.
     * @return boolean TRUE if object is a class
     */
    public function isClass() {
        return !$this->_interface && !$this->_trait;
    }

    /** Constructs an ordinary class (not an interface or an exception).
     * @return boolean
     */
    public function isOrdinaryClass() {
        return ($this->isClass() && !$this->isException()) ? TRUE : FALSE;
    }

    /** Constructs an exception.
     * @return boolean TRUE if object is an exception
     */
    public function isException() {
        return (strtolower($this->_superclass) == 'exception') ? TRUE : FALSE;
    }

    /** Returns the known subclasses of this class
     * @return classDoc[]
     */
    public function subclasses() {
        $return = [];
        foreach ($this->_root->classes() as $classDoc) {
            if ($classDoc->subclassOf($this)) $return[] = $classDoc;
        }
        return $return;
    }

    /** Merges the details of the superclass with this class.
     * @param string $superClassName The name of the root class
     */
    public function mergeSuperClassData($superClassName = NULL) {
        if (!$superClassName) {
            $superClassName = $this->superclass();
        }
        if ($superClassName) {
            $parent =& $this->_root->classNamed($superClassName);
            if ($parent->superclass()) {
                # Merge parents superclass data first by recursing
                $this->mergeSuperClassData($parent->superclass());
            }
        }

        if (isset($parent)) {
            $phpapi = $this->_root->phpapi();

            # Merge class tags array
            $tags =& $parent->tags();
            if ($tags) {
                foreach ($tags as $name => $tag) {
                    if (!isset($this->_tags[$name])) {
                        $phpapi->verbose('> Merging class '.$this->name().' with tags from parent '.$parent->name());
                        if (is_array($tag)) {
                            foreach ($tags[$name] as $key => $tag) {
                                $this->_tags[$name][$key] =& $tags[$name][$key];
                                $this->_tags[$name][$key]->setParent($this);
                            }
                        } else {
                            $this->_tags[$name] =& $tags[$name];
                            $this->_tags[$name]->setParent($this);
                        }
                    }
                }
            }

            # Merge method data
            $methods =& $this->methods();
            foreach ($methods as $name => $method) {
                $parentMethod =& $parent->methodNamed($name);
                if ($parentMethod) {
                    # Tags
                    $tags =& $parentMethod->tags();
                    if ($tags) {
                        foreach ($tags as $tagName => $tag) {
                            if (!isset($methods[$name]->_tags[$tagName])) {
                                $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with tag '.$tagName.' from parent '.$parent->name().':'.$parentMethod->name());
                                if (is_array($tag)) {
                                    foreach ($tags[$tagName] as $key => $tag) {
                                        $methods[$name]->_tags[$tagName][$key] =& $tags[$tagName][$key];
                                        $methods[$name]->_tags[$tagName][$key]->setParent($this);
                                    }
                                } else {
                                    $methods[$name]->_tags[$tagName] =& $tags[$tagName];
                                    $methods[$name]->_tags[$tagName]->setParent($this);
                                }
                            }
                        }
                    }

                    # Method parameters
                    foreach ($parentMethod->parameters() as $paramName => $param) {
                        if (isset($methods[$name]->_parameters[$paramName])) {
                            $type =& $methods[$name]->_parameters[$paramName]->type();
                        }
                        if (!isset($methods[$name]->_parameters[$paramName]) || $type->typeName() == 'mixed') {
                            $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with parameter '.$paramName.' from parent '.$parent->name().':'.$parentMethod->name());
                            $paramType =& $param->type();
                            $methods[$name]->_parameters[$paramName] =& new fieldDoc($paramName, $methods[$name], $this->_root);
                            $methods[$name]->_parameters[$paramName]->set('type', new type($paramType->typeName(), $this->_root));
                        }
                    }

                    # Method return type
                    if ($parentMethod->returnType() && $methods[$name]->_returnType->typeName() == 'void') {
                        $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with return type from parent '.$parent->name().':'.$parentMethod->name());
                        $methods[$name]->_returnType = $parentMethod->returnType();
                    }

                    # Method thrown exceptions
                    foreach ($parentMethod->thrownExceptions() as $exceptionName => $exception) {
                        if (!isset($methods[$name]->_throws[$exceptionName])) {
                            $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with exception '.$exceptionName.' from parent '.$parent->name().':'.$parentMethod->name());
                            $methods[$name]->_throws[$exceptionName] =& $exception;
                        }
                    }
                }
            }
        }
    }
}
