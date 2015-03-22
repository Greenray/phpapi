<?php
# phpapi: The PHP Documentation Creator

/** This generates the package-frame.html file that lists the interfaces and
 * classes in a given package for displaying in the lower-left frame of the
 * frame-formatted default output.
 *
 * @file      doclets/standard/packageFrameWriter.php
 * @version   2.0
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
        $phpapi        =& $this->_doclet->phpapi();
        $this->_output =& $this->_allItems($rootDoc, $phpapi);
        $this->_write('allitems.html', __('Полный список'), FALSE);

        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_depth  = $package->depth() + 1;
            $this->_output =& $this->_buildFrame($package, $phpapi);
            $this->_write($package->asPath().DS.'package-frame.html', $package->name(), FALSE);
        }
    }

    /** Build package frame
     * @return str
     */
    function &_buildFrame(&$package, $phpapi) {
        $output = [];
//        echo '<body id="frame">';
//        echo '<h1><a href="package-summary.html" target="main">', $package->name(), '</a></h1>';
        $output['name'] = $package->name();
        $classes =& $package->ordinaryClasses();
        if ($classes && is_array($classes)) {
            ksort($classes);
//            echo '<h2>Classes</h2>';
//            echo '<ul>';
            foreach ($classes as $name => $class) {
                $output['classes'][$name]['path'] = str_repeat('../', $package->depth() + 1).$classes[$name]->asPath();
                $output['classes'][$name]['name'] = $classes[$name]->name();
//                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $classes[$name]->asPath(), '" target="main">', $classes[$name]->name(), '</a></li>';
            }
//            echo '</ul>';
        }

        $interfaces =& $package->interfaces();
        if ($interfaces && is_array($interfaces)) {
            ksort($interfaces);
//            echo '<h2>Interfaces</h2>';
//            echo '<ul>';
            foreach ($interfaces as $name => $interface) {
                $output['interfaces'][$name]['path'] = str_repeat('../', $package->depth() + 1).$interfaces[$name]->asPath();
                $output['interfaces'][$name]['name'] = $interfaces[$name]->name();
//                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $interfaces[$name]->asPath(), '" target="main">', $interfaces[$name]->name(), '</a></li>';
            }
//            echo '</ul>';
        }

        $traits =& $package->traits();
        if ($traits && is_array($traits)) {
            ksort($traits);
//            echo '<h2>Traits</h2>';
//            echo '<ul>';
            foreach ($traits as $name => $trait) {
                $output['traits'][$name]['path'] = str_repeat('../', $package->depth() + 1).$traits[$name]->asPath();
                $output['traits'][$name]['name'] = $traits[$name]->name();
//                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $traits[$name]->asPath(), '" target="main">', $traits[$name]->name(), '</a></li>';
            }
//            echo '</ul>';
        }

        $exceptions =& $package->exceptions();
        if ($exceptions && is_array($exceptions)) {
            ksort($exceptions);
//            echo '<h2>Exceptions</h2>';
//            echo '<ul>';
            foreach ($exceptions as $name => $exception) {
                $output['exceptions'][$name]['path'] = str_repeat('../', $package->depth() + 1).$exceptions[$name]->asPath();
                $output['exceptions'][$name]['name'] = $exceptions[$name]->name();
//                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $exceptions[$name]->asPath(), '" target="main">', $exceptions[$name]->name(), '</a></li>';
            }
//            echo '</ul>';
        }

        $functions =& $package->functions();
        if ($functions && is_array($functions)) {
            ksort($functions);
//            echo '<h2>Functions</h2>';
//            echo '<ul>';
            foreach ($functions as $name => $function) {
                $output['functions'][$name]['path'] = str_repeat('../', $package->depth() + 1).$functions[$name]->asPath();
                $output['functions'][$name]['name'] = $functions[$name]->name();
//                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $functions[$name]->asPath(), '" target="main">', $functions[$name]->name(), '</a></li>';
            }
//            echo '</ul>';
        }

        $globals =& $package->globals();
        if ($globals && is_array($globals)) {
            ksort($globals);
//            echo '<h2>Globals</h2>';
//            echo '<ul>';
            foreach ($globals as $name => $global) {
                $output['globals'][$name]['path'] = str_repeat('../', $package->depth() + 1).$globals[$name]->asPath();
                $output['globals'][$name]['name'] = $globals[$name]->name();
//                echo '<li><a href="', str_repeat('../', $package->depth() + 1), $globals[$name]->asPath(), '" target="main">', $globals[$name]->name(), '</a></li>';
            }
//            echo '</ul>';
        }

//        echo '</body>';
        $tpl = new template($phpapi->getOption('doclet'), 'package-frame');
        ob_start();

        echo $tpl->parse($output);

        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    /** Build all items frame
     * @return str
     */
    function &_allItems(&$rootDoc, $phpapi) {
        $output = [];
        $classes =& $rootDoc->classes();
        if ($classes) {
            ksort($classes);
            foreach ($classes as $name => $class) {
                $package =& $classes[$name]->containingPackage();
                $output['classes'][$name]['path']    = $classes[$name]->asPath();
                $output['classes'][$name]['name']    = $classes[$name]->name();
                $output['classes'][$name]['package'] = $classes[$name]->packageName();
            }
        }

        $functions =& $rootDoc->functions();
        if ($functions) {
            ksort($functions);
            foreach ($functions as $name => $function) {
                $package =& $functions[$name]->containingPackage();
                $output['functions'][$name]['path']    = $functions[$name]->asPath();
                $output['functions'][$name]['name']    = $functions[$name]->name();
                $output['functions'][$name]['package'] = $functions[$name]->packageName();
            }
        }

        $globals =& $rootDoc->globals();
        if ($globals) {
            ksort($globals);
            foreach ($globals as $name => $global) {
                $package =& $globals[$name]->containingPackage();
                $output['globals'][$name]['path']    = $globals[$name]->asPath();
                $output['globals'][$name]['name']    = $globals[$name]->name();
                $output['globals'][$name]['package'] = $globals[$name]->packageName();
            }
        }

        $tpl = new template($phpapi->getOption('doclet'), 'allitems');
        ob_start();

        echo $tpl->parse($output);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
