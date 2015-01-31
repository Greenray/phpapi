<?php
# idxCMS Flat Files Content Management Sysytem

/** Нolds the information from one run of phpAPI.
 * Particularly the packages, classes and options specified by the user.
 * It is  the root of the parsed tokens and is passed to the doclet to be formatted into output.
 * @file      classes/rootDoc.php
 * @version   1.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   phpAPI
 */

class rootDoc extends Doc {

    /** Reference to the phpAPI application object.
     * @var phpAPI
     */
    public $_phpAPI = NULL;

    /** The parsed packages.
     * @var packageDoc[]
     */
    public $_packages = array();

    /** The parsed contents of the source files.
     * @var array
     */
    public $_sources = array();

    /** Constructor.
     * @param phpAPI phpAPI Application object
     */
    public function rootDoc(&$phpAPI) {
        # Set a reference to application object
        $this->_phpAPI = & $phpAPI;
        $overview = $phpAPI->getOption('overview');
        # Parse overview file
        if (isset($overview)) {
            if (is_file($overview)) {
                $phpAPI->message('Reading overview file "'.$overview.'".');
                $text = $this->getFileContents($overview);
                if (!empty($text)) {
                    $text = str_replace(["\r\n", "\n\r", "\r", "\n"], '<br>', $text);
                    $this->_data = $phpAPI->processDocComment('/** '.$text.' */', $this);
                    $this->mergeData();
                }
            } else {
                $phpAPI->warning('Cannot find overview file "'.$overview.'".');
            }
        }
    }

    /** Add a package to this root.
     * @param PackageDoc package
     */
    public function addPackage(&$package) {
        $this->_packages[$package->name()] = & $package;
    }

    /** Add a source file to this root.
     * @param str filename
     * @param str source
     * @param array fileData
     */
    public function addSource($filename, $source, $fileData) {
        $this->_sources[substr($filename, strlen($this->_phpAPI->sourcePath()) + 1)] = [$source, $fileData];
    }

    /** Return a reference to the phpAPI application object.
     * @return phpAPI.
     */
    function &phpAPI() {
        return $this->_phpAPI;
    }

    /** Return a reference to the set options.
     * @return str[] An array of strings
     */
    function &options() {
        return $this->_phpAPI->options();
    }

    /** Return a reference to the packages to be documented.
     * @return PackageDoc[]
     */
    function &packages() {
        return $this->_packages;
    }

    /** Return a reference to the source files to be documented.
     * @return str[]
     */
    function &sources() {
        return $this->_sources;
    }

    /** Return a reference to the classes and interfaces to be documented.
     * @return ClassDoc[]
     */
    function &classes() {
        $classes = array();
        $packages = $this->packages(); # Not by reference so as not to move the internal array pointer
        foreach ($packages as $name => $package) {
            $packageClasses = $this->_packages[$name]->allClasses(); # Not by reference so as not to move the internal array pointer
            if ($packageClasses) {
                foreach ($packageClasses as $key => $pack) {
                    $classes[$key.'.'.$name] = & $packageClasses[$key];
                }
            }
        }
        ksort($classes);
        return $classes;
    }

    /** Return a reference to the functions to be documented.
     * @return MethodDoc[]
     */
    function &functions() {
        $functions = array();
        $packages = $this->packages(); # Not by reference so as not to move the internal array pointer
        foreach ($packages as $name => $package) {
            $packageFunctions = $this->_packages[$name]->functions(); # Not by reference so as not to move the internal array pointer
            if ($packageFunctions) {
                foreach ($packageFunctions as $key => $pack) {
                    $functions[$name.'.'.$key] = & $packageFunctions[$key];
                }
            }
        }
        return $functions;
    }

    /** Return a reference to the globals to be documented.
     * @return FieldDoc[]
     */
    function &globals() {
        $globals = array();
        $packages = $this->packages(); # Not by reference so as not to move the internal array pointer
        foreach ($packages as $name => $package) {
            $packageGlobals = $this->_packages[$name]->globals(); # Not by reference so as not to move the internal array pointer
            if ($packageGlobals) {
                foreach ($packageGlobals as $key => $pack) {
                    $globals[$name.'.'.$key] = & $packageGlobals[$key];
                }
            }
        }
        ksort($globals);
        return $globals;
    }

    /** Return a reference to a packageDoc for the specified package name.
     * If a package of the requested name does not exist, this method will create the
     * package object, add it to the root and return it.
     * @param str name Package name
     * @param bool create Create package if it does not exist
     * @return PackageDoc
     */
    function &packageNamed($name, $create = FALSE) {
        $return = NULL;
        if (isset($this->_packages[$name])) {
            $return = & $this->_packages[$name];
        } elseif ($create) {
            $newPackage = & new packageDoc($name, $this);
            $this->addPackage($newPackage);
            $return = & $newPackage;
        }
        return $return;
    }

    /** Return a reference to a classDoc for the specified class/interface name.
     * @param str name Class name
     * @return ClassDoc
     */
    function &classNamed($name) {
        $class = NULL;
        $pos = strrpos($name, '\\');
        if ($pos != FALSE) {
            $package = substr($name, 0, $pos);
            $name = substr($name, $pos + 1);
        }
        if (isset($package)) {
            if (isset($this->_packages[$package])) {
                $class = & $this->_packages[$package]->findClass($name);
            }
        } else {
            $packages = $this->_packages; # We do this copy so as not to upset the internal pointer of the array outside this scope
            foreach ($packages as $packageName => $package) {
                $class = & $package->findClass($name);
                if ($class != NULL) {
                    break;
                }
            }
        }
        return $class;
    }

}
