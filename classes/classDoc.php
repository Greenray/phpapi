<?php
/** Represents a PHP class and provides access to information about the class,
 * class's comment and tags, and the members of the class.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/classDoc.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

class classDoc extends elementDoc {

    /** Is this class abstract.
     * @var boolean
     */
    public $abstract = FALSE;

    /** The class constants.
     * @var fieldDoc[]
     */
    public $constants = [];

    /** The class fields.
     * @var fieldDoc[]
     */
    public $fields = [];

    /** Is this an interface?
     * @var boolean
     */
    public $interface = FALSE;

    /** Interfaces this class implements or this interface extends.
     * @var classDoc[]
     */
    public $interfaces = [];

    /** The class methods.
     * @var methodDoc[]
     */
    public $methods = [];

    /** The super class.
     * @var string
     */
    public $superclass = NULL;

    /** Is this a trait?
     * @var boolean
     */
    public $trait = FALSE;

    /** Traits this class uses.
     * @var classDoc[]
     */
    public $traits = [];

    /** Constructor.
     *
     * @param  string  $name       The name of this element
     * @param  rootDoc $filename   The filename of the source file this element is in
     * @param  integer $lineNumber The line number of the source file this element is at
     * @param  string  $sourcePath The source path containing the source file
     * @return void
     */
    public function classDoc($name, &$root, $filename, $lineNumber, $sourcePath) {
        $this->name       = $name;
        $this->root       = &$root;
        $this->filename   = $filename;
        $this->lineNumber = $lineNumber;
        $this->sourcePath = $sourcePath;
    }

    /** Adds a constant to this class.
     *
     * @param  fieldDoc[] $constant Link to a constant
     * @return void
     */
    public function addConstant(&$constant) {
        if (!isset($this->constants[$constant->name()])) $this->constants[$constant->name()] = &$constant;
    }

    /** Adds a field to this class.
     *
     * @param  fieldDoc[] $field Link to a field
     * @return void
     */
    public function addField(&$field) {
        if (!isset($this->fields[$field->name()])) $this->fields[$field->name()] = &$field;
    }

    /** Adds a method to this class.
     *
     * @param  methodDoc[] $method Link to a method
     * @return void
     */
    public function addMethod(&$method) {
        if (isset($this->methods[$method->name()])) {
            $phpapi = &$this->root->phpapi();
            echo LF;
            $phpapi->warning('Found method '.$method->name().' again, overwriting previous version');
        }
        $this->methods[$method->name()] = &$method;
    }

    /** Returns constants in this class.
     *
     * @return fieldDoc[] List of constants
     */
    public function &constants() {
        return $this->constants;
    }

    /** Returns fields in this class.
     *
     * @return fieldDoc[] List of fields
     */
    public function &fields() {
        return $this->fields;
    }

    /** Returns a field in this class
     * .
     * @return fieldDoc[] Field from current class
     */
    public function &fieldNamed($fieldName) {
        $return = NULL;
        if (isset($this->fields[$fieldName])) $return = &$this->fields[$fieldName];
        return $return;
    }

    /** Returns the methods in this class.
     *
     * @param  boolean     $regularOnly Do not return constructors and destructors
     * @return methodDoc[]              List of class methods
     */
    public function &methods($regularOnly = FALSE) {
        if ($regularOnly) {
            $return = [];
            foreach ($this->methods as $method) {
                if (!$method->isConstructor() && !$method->isDestructor()) $return[] = $method;
            }
        } else $return = $this->methods;
        return $return;
    }

    /** Returns a method in this class.
     *
     * @return methodDoc[] Method from current class
     */
    public function &methodNamed($methodName) {
        $return = NULL;
        if (isset($this->methods[$methodName])) $return = &$this->methods[$methodName];
        return $return;
    }

    /** Returns constructor for this class.
     *
     * @return methodDoc[] Constructor
     */
    public function &constructor() {
        $return = NULL;
        foreach ($this->methods as $method) {
            if ($method->isConstructor()) {
                $return = &$method;
                break;
            }
        }
        return $return;
    }

    /** Returns destructor for this class.
     *
     * @return methodDoc[] Destructor
     */
    public function &destructor() {
        $return = NULL;
        foreach ($this->methods as $method) {
            if ($method->isDestructor()) {
                $return = &$method;
                break;
            }
        }
        return $return;
    }

    /** Returns interfaces implemented by this class or interfaces extended by this interface.
     *
     * @return classDoc[] List of interfaces
     */
    public function &interfaces() {
        return $this->interfaces;
    }

    /** Returns an interface in this class.
     *
     * @return classDoc[] Interface from current class
     */
    public function &interfaceNamed($interfaceName) {
        $return = NULL;
        if (isset($this->interfaces[$interfaceName])) $return = &$this->interfaces[$interfaceName];
        return $return;
    }

    /** Returns traits used by this class
     *
     * @return classDoc[]
     */
    public function &traits() {
        return $this->traits;
    }

    /** Returns an trait in this class.
     *
     * @return classDoc[] Trait from current class
     */
    public function &traitNamed($traitName) {
        $return = NULL;
        if (isset($this->traits[$traitName])) $return = &$this->traits[$traitName];
        return $return;
    }

    /** Returns true if this class is abstract.
     *
     * @return boolean
     */
    public function isAbstract() {
        return $this->abstract;
    }

    /** Returns true if this element is an interface.
     *
     * @return boolean
     */
    public function isInterface() {
        return $this->interface;
    }

    /** Returns true if this element is a trait.
     *
     * @return boolean
     */
    public function isTrait() {
        return $this->trait;
    }

    /** Tests whether this class is a subclass of the specified class.
     *
     * @param  classDoc $cd Specified class
     * @return boolean      The result of the validation
     */
    public function subclassOf($cd) {
        return ($this->superclass == $cd->name()) ? TRUE : FALSE;
    }

    /** Returns the superclass of this class.
     *
     * @return classDoc[]
     */
    public function superclass() {
        return $this->superclass;
    }

    /** Constructs a class.
     *
     * @note   Interfaces are not classes.
     * @return boolean TRUE if object is a class
     */
    public function isClass() {
        return !$this->interface && !$this->trait;
    }

    /** Constructs an ordinary class (not an interface or an exception).
     *
     * @return boolean
     */
    public function isOrdinaryClass() {
        return ($this->isClass() && !$this->isException()) ? TRUE : FALSE;
    }

    /** Constructs an exception.
     *
     * @return boolean TRUE if object is an exception
     */
    public function isException() {
        return (strtolower($this->superclass) == 'exception') ? TRUE : FALSE;
    }

    /** Returns the known subclasses of this class.
     *
     * @return classDoc[]
     */
    public function subclasses() {
        $return = [];
        foreach ($this->root->classes() as $classDoc) {
            if ($classDoc->subclassOf($this)) $return[] = $classDoc;
        }
        return $return;
    }

    /** Merges the details of the superclass with this class.
     *
     * @param string $superClassName The name of the root class
     */
    public function mergeSuperClassData($superClassName = NULL) {
        if (!$superClassName) {
            $superClassName = $this->superclass();
        }
        if ($superClassName) {
            $parent = &$this->root->classNamed($superClassName);
            if ($parent->superclass()) {
                # Merge parents superclass data first by recursing
                $this->mergeSuperClassData($parent->superclass());
            }
        }

        if (isset($parent)) {
            $phpapi = $this->root->phpapi();

            # Merge class tags array
            $tags = &$parent->tags();
            if ($tags) {
                foreach ($tags as $name => $tag) {
                    if (!isset($this->tags[$name])) {
                        $phpapi->verbose('> Merging class '.$this->name().' with tags from parent '.$parent->name());
                        if (is_array($tag)) {
                            foreach ($tags[$name] as $key => $tag) {
                                $this->tags[$name][$key] = &$tags[$name][$key];
                                $this->tags[$name][$key]->setParent($this);
                            }
                        } else {
                            $this->tags[$name] = &$tags[$name];
                            $this->tags[$name]->setParent($this);
                        }
                    }
                }
            }

            # Merge method data
            $methods = &$this->methods();
            foreach ($methods as $name => $method) {
                $parentMethod = &$parent->methodNamed($name);
                if ($parentMethod) {
                    # Tags
                    $tags = &$parentMethod->tags();
                    if ($tags) {
                        foreach ($tags as $tagName => $tag) {
                            if (!isset($methods[$name]->tags[$tagName])) {
                                $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with tag '.$tagName.' from parent '.$parent->name().':'.$parentMethod->name());
                                if (is_array($tag)) {
                                    foreach ($tags[$tagName] as $key => $tag) {
                                        $methods[$name]->tags[$tagName][$key] = &$tags[$tagName][$key];
                                        $methods[$name]->tags[$tagName][$key]->setParent($this);
                                    }
                                } else {
                                    $methods[$name]->tags[$tagName] = &$tags[$tagName];
                                    $methods[$name]->tags[$tagName]->setParent($this);
                                }
                            }
                        }
                    }

                    # Method parameters
                    foreach ($parentMethod->parameters() as $paramName => $param) {
                        if (isset($methods[$name]->parameters[$paramName])) {
                            $type = &$methods[$name]->parameters[$paramName]->type();
                        }
                        if (!isset($methods[$name]->parameters[$paramName]) || $type->typeName() == 'mixed') {
                            $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with parameter '.$paramName.' from parent '.$parent->name().':'.$parentMethod->name());
                            $paramType = &$param->type();
                            $methods[$name]->parameters[$paramName] = &new fieldDoc($paramName, $methods[$name], $this->root);
                            $methods[$name]->parameters[$paramName]->set('type', new type($paramType->typeName(), $this->root));
                        }
                    }

                    # Method return type
                    if ($parentMethod->returnType() && $methods[$name]->returnType->typeName() == 'void') {
                        $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with return type from parent '.$parent->name().':'.$parentMethod->name());
                        $methods[$name]->returnType = $parentMethod->returnType();
                    }

                    # Method thrown exceptions
                    foreach ($parentMethod->thrownExceptions() as $exceptionName => $exception) {
                        if (!isset($methods[$name]->throws[$exceptionName])) {
                            $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with exception '.$exceptionName.' from parent '.$parent->name().':'.$parentMethod->name());
                            $methods[$name]->throws[$exceptionName] = &$exception;
                        }
                    }
                }
            }
        }
    }
}
