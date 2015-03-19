<?php
# phpapi: The PHP Documentation Creator

/** This generates the package-frame.html file that lists the interfaces and
 * classes in a given package for displaying in the lower-left frame of the
 * frame-formatted default output.
 *
 * @file      doclets/standard/packageFrameWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class packageFrameWriter extends htmlWriter {

    /** Build the package frame index.
     * @param Doclet doclet
     */
    public function packageFrameWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $rootDoc       =& $this->_doclet->rootDoc();
        $this->_output =& $this->_allItems($rootDoc);
        $this->_write('allitems.html', 'All Items', FALSE);

        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_depth  = $package->depth() + 1;
            $this->_output =& $this->_buildFrame($package);
            $this->_write($package->asPath().DS.'package-frame.html', $package->name(), FALSE);
        }
    }

    /** Build package frame
     * @return str
     */
    function &_buildFrame(&$package) {

        ob_start();

        echo '<body id="frame">';
        echo '<h1><a href="package-summary.html" target="main">', $package->name(), '</a></h1>';

        $classes =& $package->ordinaryClasses();
        if ($classes && is_array($classes)) {
            ksort($classes);
            echo '<h2>Classes</h2>';
            echo '<ul>';
            foreach ($classes as $name => $class) {
                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $classes[$name]->asPath(), '" target="main">', $classes[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        $interfaces =& $package->interfaces();
        if ($interfaces && is_array($interfaces)) {
            ksort($interfaces);
            echo '<h2>Interfaces</h2>';
            echo '<ul>';
            foreach ($interfaces as $name => $interface) {
                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $interfaces[$name]->asPath(), '" target="main">', $interfaces[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        $traits =& $package->traits();
        if ($traits && is_array($traits)) {
            ksort($traits);
            echo '<h2>Traits</h2>';
            echo '<ul>';
            foreach ($traits as $name => $trait) {
                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $traits[$name]->asPath(), '" target="main">', $traits[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        $exceptions =& $package->exceptions();
        if ($exceptions && is_array($exceptions)) {
            ksort($exceptions);
            echo '<h2>Exceptions</h2>';
            echo '<ul>';
            foreach ($exceptions as $name => $exception) {
                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $exceptions[$name]->asPath(), '" target="main">', $exceptions[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        $functions =& $package->functions();
        if ($functions && is_array($functions)) {
            ksort($functions);
            echo '<h2>Functions</h2>';
            echo '<ul>';
            foreach ($functions as $name => $function) {
                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $functions[$name]->asPath(), '" target="main">', $functions[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        $globals =& $package->globals();
        if ($globals && is_array($globals)) {
            ksort($globals);
            echo '<h2>Globals</h2>';
            echo '<ul>';
            foreach ($globals as $name => $global) {
                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $globals[$name]->asPath(), '" target="main">', $globals[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        echo '</body>';

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /** Build all items frame
     * @return str
     */
    function &_allItems(&$rootDoc) {

        ob_start();

        echo '<body id="frame">';
        echo '<h1>All Items</h1>';

        $classes =& $rootDoc->classes();
        if ($classes) {
            ksort($classes);
            echo '<h2>Classes</h2>';
            echo '<ul>';
            foreach ($classes as $name => $class) {
                $package =& $classes[$name]->containingPackage();
                if ($class->isInterface()) {
                    echo '<li><em><a href="', $classes[$name]->asPath(), '" title="', $classes[$name]->packageName(), '" target="main">', $classes[$name]->name(), '</a></em></li>';
                } elseif ($class->isTrait()) {
                    echo '<li><em><a href="', $classes[$name]->asPath(), '" title="', $classes[$name]->packageName(), '" target="main">', $classes[$name]->name(), '</a></em></li>';
                } else {
                    echo '<li><a href="', $classes[$name]->asPath(), '" title="', $classes[$name]->packageName(), '" target="main">', $classes[$name]->name(), '</a></li>';
                }
            }
            echo '</ul>';
        }

        $functions =& $rootDoc->functions();
        if ($functions) {
            ksort($functions);
            echo '<h2>Functions</h2>';
            echo '<ul>';
            foreach ($functions as $name => $function) {
                $package =& $functions[$name]->containingPackage();
                echo '<li><a href="', $package->asPath(), DS.'package-functions.html#', $functions[$name]->name(), '" title="', $functions[$name]->packageName(), '" target="main">', $functions[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        $globals =& $rootDoc->globals();
        if ($globals) {
            ksort($globals);
            echo '<h2>Globals</h2>';
            echo '<ul>';
            foreach ($globals as $name => $global) {
                $package =& $globals[$name]->containingPackage();
                echo '<li><a href="', $package->asPath(), DS.'package-globals.html#', $globals[$name]->name(), '" title="', $globals[$name]->packageName(), '" target="main">', $globals[$name]->name(), '</a></li>';
            }
            echo '</ul>';
        }

        echo '</body>';

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
