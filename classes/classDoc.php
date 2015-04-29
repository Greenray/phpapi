<?php
/**
 * Represents a PHP class and provides access to information about the class,
 * class's comment and tags, and the members of the class.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      classes/classDoc.php
 * @package   phpapi
 */

class classDoc extends elementDoc {

    /** @var boolean Is this class abstract? */
    public $abstract = FALSE;

    /** @var fieldDoc Class constants */
    public $constants = [];

    /** @var fieldDoc Class fields */
    public $fields = [];

    /** @var array Required files or files to be included */
    public $includes = [];

    /** @var boolean Is this an interface? */
    public $interface = FALSE;

    /** @var classDoc Interfaces this class implements or this interface extends */
    public $interfaces = [];

    /** @var methodDoc Class methods */
    public $methods = [];

    /** @var string Super class */
    public $superclass = NULL;

    /** @var boolean Is this a trait? */
    public $trait = FALSE;

    /** @var classDoc Traits this class uses */
    public $traits = [];

    /**
     * Constructor.
     *
     * @param string  $name       Name of this element
     * @param rootDoc &$root      Object reference
     * @param string  $filename   Filename of the source file this element is in
     * @param integer $lineNumber Line number of the source file this element is at
     * @param string  $sourcePath Source path containing the source file
     */
    public function __construct($name, &$root, $filename, $lineNumber, $sourcePath) {
        $this->name       = $name;
        $this->root       = &$root;
        $this->filename   = $filename;
        $this->lineNumber = $lineNumber;
        $this->sourcePath = $sourcePath;
    }

    /**
     * Returns constructor for this class.
     *
     * @return methodDoc
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

    /**
     * Returns destructor for this class.
     *
     * @return methodDoc
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

    /**
     * Returns TRUE if object is a class.
     *
     * @return boolean
     */
    public function isClass() {
        return !$this->interface && !$this->trait;
    }

    /**
     * Returns TRUE if object is an exception.
     *
     * @return boolean
     */
    public function isException() {
        return (strtolower($this->superclass) === 'exception') ? TRUE : FALSE;
    }

    /**
     * Returns TRUE if this element is an interface.
     *
     * @return boolean
     */
    public function isInterface() {
        return $this->interface;
    }

    /**
     * Constructs an ordinary class (not an interface or an exception).
     *
     * @return boolean
     */
    public function isOrdinaryClass() {
        return ($this->isClass() && !$this->isException()) ? TRUE : FALSE;
    }

    /**
     * Merges the details of the superclass with this class.
     * This function is recursive.
     *
     * @param string $superClassName Name of the root class (default = NULL)
     */
    public function mergeSuperClassData($superClassName = NULL) {
        if (!$superClassName) {
            $superClassName = $this->superclass;
        }
        if ($superClassName) {
            $parent = &$this->root->classNamed($superClassName);
            if ($parent->superclass) {
                #
                # Merge parents superclass data first by recursing
                #
                $this->mergeSuperClassData($parent->superclass);
            }
        }

        if (isset($parent)) {
            #
            # Merge class tags array
            #
            $tags = &$parent->tags;
            if ($tags) {
                foreach ($tags as $name => $tag) {
                    if (!isset($this->tags[$name])) {

                        $this->root->phpapi->verbose('> Merging class '.$this->name.' with tags from parent '.$parent->name);

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
            #
            # Merge method data
            #
            $methods = &$this->methods();
            foreach ($methods as $name => $method) {
                $parentMethod = (isset($parent->methods[$name])) ? $parent->methods[$name] : NULL;
                if ($parentMethod) {
                    #
                    # Tags
                    #
                    $tags = &$parentMethod->tags;
                    if ($tags) {
                        foreach ($tags as $tagName => $tag) {
                            if (!isset($methods[$name]->tags[$tagName])) {

                                $this->root->phpapi->verbose('> Merging method '.$this->name.':'.$name.' with tag '.$tagName.' from parent '.$parent->name.':'.$parentMethod->name);

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
                    #
                    # Method parameters
                    #
                    foreach ($parentMethod->parameters as $paramName => $param) {
                        if (isset($methods[$name]->parameters[$paramName])) {
                            $type = &$methods[$name]->parameters[$paramName]->type;
                        }
                        if (!isset($methods[$name]->parameters[$paramName]) || ($type->typeName === 'mixed')) {

                            $this->root->phpapi->verbose('> Merging method '.$this->name.':'.$name.' with parameter '.$paramName.' from parent '.$parent->name.':'.$parentMethod->name);

                            $paramType = &$param->type;
                            $methods[$name]->parameters[$paramName] = &new fieldDoc($paramName, $methods[$name], $this->root);
                            $methods[$name]->parameters[$paramName]->set('type', new type($paramType->typeName, $this->root));
                        }
                    }
                    #
                    # Method return type
                    #
                    if ($parentMethod->returnType && $methods[$name]->returnType->typeName === 'void') {

                        $this->root->phpapi->verbose('> Merging method '.$this->name.':'.$name.' with return type from parent '.$parent->name.':'.$parentMethod->name);

                        $methods[$name]->returnType = $parentMethod->returnType;
                    }
                    #
                    # Method thrown exceptions
                    #
                    foreach ($parentMethod->throws as $exceptionName => $exception) {
                        if (!isset($methods[$name]->throws[$exceptionName])) {

                            $this->root->phpapi->verbose('> Merging method '.$this->name.':'.$name.' with exception '.$exceptionName.' from parent '.$parent->name.':'.$parentMethod->name);

                            $methods[$name]->throws[$exceptionName] = &$exception;
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns the array of methods in current class.
     *
     * @param  boolean $regularOnly Do not return constructors and destructors (default = FALSE)
     * @return methodDoc[]
     */
    public function &methods($regularOnly = FALSE) {
        if ($regularOnly) {
            $return = [];
            foreach ($this->methods as $method) {
                if (!$method->isConstructor() && !$method->isDestructor()) {
                    $return[] = $method;
                }
            }
        } else $return = $this->methods;
        return $return;
    }

    /**
     * Returns the array of known subclasses of this class.
     *
     * @return array classDoc
     */
    public function subclasses() {
        $return  = [];
        $classes = $this->root->classes();
        foreach ($classes as $subClass) {
            if ($subClass->superclass === $this->name) $return[] = $subClass;
        }
        return $return;
    }
}
