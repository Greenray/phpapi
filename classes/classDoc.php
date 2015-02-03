<?php
# phpapi: The PHP Documentation Creator

/** Represents a PHP class and provides access to information about the class,
 * the class' comment and tags, and the members of the class. A classDoc only
 * exists if it was processed in this run of phpapi. References to classes
 * which may or may not have been processed in this run are referred to using
 * type (which can be converted to classDoc, if possible).
 * @file      classes/classDoc.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   phpapi
 */

class classDoc extends ProgramElementDoc {

    /** The super class.
     * @var string
     */
    public $_superclass = NULL;

    /** Is this an interface?
     * @var booleanean
     */
    public $_interface = FALSE;

    /** Is this a trait?
     * @var booleanean
     */
    public $_trait = FALSE;

    /** The class constants.
     * @var fieldDoc[]
     */
    public $_constants = array();

    /** The class fields.
     * @var fieldDoc[]
     */
    public $_fields = array();

    /** The class methods.
     * @var methodDoc[]
     */
    public $_methods = array();

    /** Interfaces this class implements or this interface extends.
     * @var classDoc[]
     */
    public $_interfaces = array();

    /** Traits this class uses.
     * @var classDoc[]
     */
    public $_traits = array();

    /** Is this class abstract.
     * @var booleanean
     */
    public $_abstract = FALSE;

    /** Constructor.
     * @param str name Name of this element
     * @param RootDoc root The root element
     * @param str filename The filename of the source file this element is in
     * @param int lineNumber The line number of the source file this element is at
     * @param str sourcePath The source path containing the source file
     */
    public function classDoc($name, &$root, $filename, $lineNumber, $sourcePath) {
        $this->_name       = $name;
        $this->_root       = & $root;
        $this->_filename   = $filename;
        $this->_lineNumber = $lineNumber;
        $this->_sourcePath = $sourcePath;
    }

    /** Add a constant to this class.
     * @param FieldDoc field
     */
    public function addConstant(&$constant) {
        if (!isset($this->_constants[$constant->name()])) {
            $this->_constants[$constant->name()] = & $constant;
        }
    }

    /** Add a field to this class.
     * @param FieldDoc field
     */
    public function addField(&$field) {
        if (!isset($this->_fields[$field->name()])) {
            $this->_fields[$field->name()] = & $field;
        }
    }

    /** Add a method to this class.
     * @param MethodDoc method
     */
    public function addMethod(&$method) {
        if (isset($this->_methods[$method->name()])) {
            $phpapi = & $this->_root->phpapi();
            echo LF;
            $phpapi->warning('Found method '.$method->name().' again, overwriting previous version');
        }
        $this->_methods[$method->name()] = & $method;
    }

    /** Return constants in this class.
     * @return FieldDoc[]
     */
    function &constants() {
        return $this->_constants;
    }

    /** Return fields in this class.
     * @return FieldDoc[]
     */
    function &fields() {
        return $this->_fields;
    }

    /** Return a field in this class.
     *
     * @return FieldDoc
     */
    function &fieldNamed($fieldName) {
        $return = NULL;
        if (isset($this->_fields[$fieldName])) {
            $return = & $this->_fields[$fieldName];
        }
        return $return;
    }

    /** Return the methods in this class.
     * @param boolean regularOnly Do not return constructors and destructors
     * @return MethodDoc[]
     */
    function &methods($regularOnly = FALSE) {
        if ($regularOnly) {
            $return = [];
            foreach ($this->_methods as $method) {
                if (!$method->isConstructor() && !$method->isDestructor()) {
                    $return[] = $method;
                }
            }
        } else {
            $return = $this->_methods;
        }
        return $return;
    }

    /** Return a method in this class.
     * @return MethodDoc
     */
    function &methodNamed($methodName) {
        $return = NULL;
        if (isset($this->_methods[$methodName])) {
            $return = & $this->_methods[$methodName];
        }
        return $return;
    }

    /** Return constructor for this class.
     * @return MethodDoc
     */
    function &constructor() {
        $return = NULL;
        foreach ($this->_methods as $method) {
            if ($method->isConstructor()) {
                $return = & $method;
                break;
            }
        }
        return $return;
    }

    /** Return destructor for this class.
     * @return MethodDoc
     */
    function &destructor() {
        $return = NULL;
        foreach ($this->_methods as $method) {
            if ($method->isDestructor()) {
                $return = & $method;
                break;
            }
        }
        return $return;
    }

    /** Return interfaces implemented by this class or interfaces extended by this interface.
     * @return ClassDoc[]
     */
    function &interfaces() {
        return $this->_interfaces;
    }

    /** Return an interface in this class.
     * @return ClassDoc
     */
    function &interfaceNamed($interfaceName) {
        $return = NULL;
        if (isset($this->_interfaces[$interfaceName])) {
            $return = & $this->_interfaces[$interfaceName];
        }
        return $return;
    }

    /** Return traits used by this class
     * @return ClassDoc[]
     */
    function &traits() {
        return $this->_traits;
    }

    /** Return an interface in this class.
     * @return ClassDoc
     */
    function &traitNamed($traitName) {
        $return = NULL;
        if (isset($this->_traits[$traitName])) {
            $return = & $this->__traits[$traitName];
        }
        return $return;
    }

    /** Return true if this class is abstract.
     * @return boolean
     */
    public function isAbstract() {
        return $this->_abstract;
    }

    /** Return true if this element is an interface.
     * @return boolean
     */
    public function isInterface() {
        return $this->_interface;
    }

    /** Return true if this element is a trait.
     * @return boolean
     */
    public function isTrait() {
        return $this->_trait;
    }

    /** Test whether this class is a subclass of the specified class.
     * @param ClassDoc cd
     * @return boolean
     */
    public function subclassOf($cd) {
        return ($this->_superclass == $cd->name()) ? TRUE : FALSE;
    }

    /** Return the superclass of this class.
     * @return ClassDoc
     */
    public function superclass() {
        return $this->_superclass;
    }

    /** Is this construct a class. Note: interfaces are not classes.
     * @return boolean
     */
    public function isClass() {
        return !$this->_interface && !$this->_trait;
    }

    /** Is this construct an ordinary class (not an interface or an exception).
     * @return boolean
     */
    public function isOrdinaryClass() {
        return ($this->isClass() && !$this->isException()) ? TRUE : FALSE;
    }

    /** Is this construct an exception.
     * @return boolean
     */
    public function isException() {
        return (strtolower($this->_superclass) == 'exception') ? TRUE : FALSE;
    }

    /** Return the known subclasses of this class
     * @return classDoc[]
     */
    public function subclasses() {
        $return = array();
        foreach ($this->_root->classes() as $classDoc) {
            if ($classDoc->subclassOf($this)) {
                $return[] = $classDoc;
            }
        }
        return $return;
    }

    /** Merge the details of the superclass with this class.
     * @param str superClassName
     */
    public function mergeSuperClassData($superClassName = NULL) {
        if (!$superClassName) {
            $superClassName = $this->superclass();
        }
        if ($superClassName) {
            $parent = & $this->_root->classNamed($superClassName);
            if ($parent->superclass()) { # Merge parents superclass data first by recursing
                $this->mergeSuperClassData($parent->superclass());
            }
        }

        if (isset($parent)) {
            $phpapi = $this->_root->phpapi();

            # Merge class tags array
            $tags = & $parent->tags();
            if ($tags) {
                foreach ($tags as $name => $tag) {
                    if (!isset($this->_tags[$name])) {
                        $phpapi->verbose('> Merging class '.$this->name().' with tags from parent '.$parent->name());
                        if (is_array($tag)) {
                            foreach ($tags[$name] as $key => $tag) {
                                $this->_tags[$name][$key] = & $tags[$name][$key];
                                $this->_tags[$name][$key]->setParent($this);
                            }
                        } else {
                            $this->_tags[$name] = & $tags[$name];
                            $this->_tags[$name]->setParent($this);
                        }
                    }
                }
            }

            # Merge method data
            $methods = & $this->methods();
            foreach ($methods as $name => $method) {
                $parentMethod = & $parent->methodNamed($name);
                if ($parentMethod) {
                    # Tags
                    $tags = & $parentMethod->tags();
                    if ($tags) {
                        foreach ($tags as $tagName => $tag) {
                            if (!isset($methods[$name]->_tags[$tagName])) {
                                $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with tag '.$tagName.' from parent '.$parent->name().':'.$parentMethod->name());
                                if (is_array($tag)) {
                                    foreach ($tags[$tagName] as $key => $tag) {
                                        $methods[$name]->_tags[$tagName][$key] = & $tags[$tagName][$key];
                                        $methods[$name]->_tags[$tagName][$key]->setParent($this);
                                    }
                                } else {
                                    $methods[$name]->_tags[$tagName] = & $tags[$tagName];
                                    $methods[$name]->_tags[$tagName]->setParent($this);
                                }
                            }
                        }
                    }
                    # Method parameters
                    foreach ($parentMethod->parameters() as $paramName => $param) {
                        if (isset($methods[$name]->_parameters[$paramName])) {
                            $type = & $methods[$name]->_parameters[$paramName]->type();
                        }
                        if (!isset($methods[$name]->_parameters[$paramName]) || $type->typeName() == 'mixed') {
                            $phpapi->verbose('> Merging method '.$this->name().':'.$name.' with parameter '.$paramName.' from parent '.$parent->name().':'.$parentMethod->name());
                            $paramType = & $param->type();
                            $methods[$name]->_parameters[$paramName] = & new fieldDoc($paramName, $methods[$name], $this->_root);
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
                            $methods[$name]->_throws[$exceptionName] = & $exception;
                        }
                    }
                }
            }
        }
    }
}
