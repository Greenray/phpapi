<?php
/**
 * Нolds the information from one run of phpapi.
 * Particularly the packages, classes and options specified by the user.
 * It is  the root of the parsed tokens and is passed to the doclet to be formatted into output.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/classes/rootDoc.php
 * @package   phpapi
 */

class rootDoc extends doc {

    /** @var packageDoc Parsed packages */
    public $packages = NULL;

    /** @var phpapi Reference to the phpapi application object */
    public $phpapi = NULL;

    /**
     * Constructor.
     * Sets Reference to application object and parses the main overview file.
     * Overview file is a markdown file, so it will be passed and represented at the main page of the documentation.
     *
     * @param phpapi &$phpapi Reference to the application object
     */
    public function __construct(&$phpapi) {
        #
        # Set a reference to application object
        #
        $this->phpapi = &$phpapi;
        $overview     = $phpapi->options['source'].$phpapi->options['overview'];
        #
        # Parse overview file
        #
        if (isset($overview)) {
            if (is_file($overview)) {
                $phpapi->verbose('Reading overview file '.$overview);
                $text = file_get_contents($overview);
                if (!empty($text)) {
                    require_once MARKDOWN.'markdownInterface.php';
                    require_once MARKDOWN.'markdown.php';
                    require_once MARKDOWN.'markdownExtra.php';

                    $parser = new markdownExtra;
                    $text   = $parser->transform($text);

                    $this->data = $phpapi->parseDocComment('/** '.$text.' */', $this);
                    $this->mergeData();
                }
            } else $phpapi->warning('Cannot find overview file '.$overview);
        }
    }

    /**
     * Returns a reference to a classDoc for the specified class/interface name.
     *
     * @param  string $name Class name
     * @return classDoc|NULL
     */
    public function &classNamed($name) {
        $class = NULL;
        $pos   = strrpos($name, '\\');
        if ($pos !== FALSE) {
            $package = substr($name, 0, $pos);
            $name    = substr($name, $pos + 1);
        }
        if (isset($package)) {
            if (isset($this->packages[$package])) $class = &$this->packages[$package]->findClass($name);
        } else {
            $packages = $this->packages;
            foreach ($packages as $packageName => $package) {
                $class = &$package->findClass($name);
                if ($class !== NULL) {
                    break;
                }
            }
        }
        return $class;
    }

    /**
     * Returns a reference to the classes and interfaces to be documented.
     *
     * @return array
     */
    public function &classes() {
        $classes  = [];
        foreach ($this->packages as $name => $package) {
            $packageClasses = $package->classes;
            if ($packageClasses) {
                foreach ($packageClasses as $key => $pack) {
                    $classes[$key.'.'.$name] = &$packageClasses[$key];
                }
            }
        }
        ksort($classes);
        return $classes;
    }

    /**
     * Returns a reference to the functions to be documented.
     *
     * @return array
     */
    public function &functions() {
        $functions = [];
        foreach ($this->packages as $name => $package) {
            $packageFunctions = $package->functions;
            if ($packageFunctions) {
                foreach ($packageFunctions as $key => $pack) {
                    $functions[$name.'.'.$key] = &$packageFunctions[$key];
                }
            }
        }
        return $functions;
    }

    /**
     * Returns a reference to the globals to be documented.
     *
     * @return array
     */
    public function &globals() {
        $globals  = [];
        foreach ($this->packages as $name => $package) {
            $packageGlobals = $package->globals;
            if ($packageGlobals) {
                foreach ($packageGlobals as $key => $pack) {
                    $globals[$name.'.'.$key] = &$packageGlobals[$key];
                }
            }
        }
        ksort($globals);
        return $globals;
    }

    /**
     * Returns a reference to a packageDoc for the specified package name.
     * If a package of the requested name does not exist, this method will create the
     * package object, add it to the root and return it.
     *
     * @param  string  $name     Package name
     * @param  boolean $create   Create package if it does not exist (default = FALSE)
     * @param  string  $overview Package description                 (default = '')
     * @return packageDoc|NULL
     */
    public function &packageNamed($name, $create = FALSE, $overview = '') {
        $return = NULL;
        if (isset($this->packages[$name])) {
            if (!empty($overview)) {
                preg_match('/^(.+)(\.(?: |\t|\n|<\/p>|<\/?h[1-6]>|<hr)|$)/sU', $overview, $matches);
                $this->packages[$name]->desc     = $matches[1];
                $this->packages[$name]->overview = $overview;
            }
            $return = &$this->packages[$name];
        } elseif ($create) {
            $newPackage = &new packageDoc($name, $this, $overview);
            $this->packages[$newPackage->name] = &$newPackage;
            $return = &$newPackage;
        }
        return $return;
    }
}
