<?php
/** This generates the package-frame.html file that lists the interfaces and
 * classes in a given package for displaying in the lower-left frame of the
 * frame-formatted default output.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlFrames/packageFrameWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlFrames
 */

class packageFrameWriter extends htmlWriter {

    /** Build the package frame index.
     * @param Doclet doclet
     */
    public function packageFrameWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $rootDoc       = &$this->doclet->rootDoc();
        $phpapi        = &$this->doclet->phpapi();
        $this->output = &$this->allItems($rootDoc, $phpapi);
        $this->write('all-items.html', __('Полный список'), FALSE);

        $packages = &$rootDoc->packages;
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->depth  = $package->depth() + 1;
            $this->output = &$this->buildFrame($package, $phpapi);
            $this->write($package->asPath().DS.'package-frame.html', $package->name(), FALSE);
        }
    }

    /** Build package frame
     * @return str
     */
    private function &buildFrame(&$package, $phpapi) {
        $output = [];
        $output['name'] = $package->name();
        $classes = &$package->ordinaryClasses();
        if ($classes && is_array($classes)) {
            ksort($classes);
            foreach ($classes as $name => $class) {
                $output['class'][$name]['path'] = str_repeat('../', $package->depth() + 1).$class->asPath();
                $output['class'][$name]['name'] = $class->name();
            }
        }

        $interfaces = &$package->interfaces();
        if ($interfaces && is_array($interfaces)) {
            ksort($interfaces);
            foreach ($interfaces as $name => $interface) {
                $output['interface'][$name]['path'] = str_repeat('../', $package->depth() + 1).$interface->asPath();
                $output['interface'][$name]['name'] = $interface->name();
            }
        }

        $traits = &$package->traits();
        if ($traits && is_array($traits)) {
            ksort($traits);
            foreach ($traits as $name => $trait) {
                $output['trait'][$name]['path'] = str_repeat('../', $package->depth() + 1).$trait->asPath();
                $output['trait'][$name]['name'] = $trait->name();
            }
        }

        $exceptions = &$package->exceptions();
        if ($exceptions && is_array($exceptions)) {
            ksort($exceptions);
            foreach ($exceptions as $name => $exception) {
                $output['exception'][$name]['path'] = str_repeat('../', $package->depth() + 1).$exception->asPath();
                $output['exception'][$name]['name'] = $exception->name();
            }
        }

        $functions = &$package->functions();
        if ($functions && is_array($functions)) {
            ksort($functions);
            foreach ($functions as $name => $function) {
                $output['function'][$name]['path'] = str_repeat('../', $package->depth() + 1).$function->asPath();
                $output['function'][$name]['name'] = $function->name();
            }
        }

        $globals = &$package->globals();
        if ($globals && is_array($globals)) {
            ksort($globals);
            foreach ($globals as $name => $global) {
                $output['global'][$name]['path'] = str_repeat('../', $package->depth() + 1).$global->asPath();
                $output['global'][$name]['name'] = $global->name();
            }
        }

        $tpl = new template($phpapi->options['doclet'], 'package-frame.tpl');
        ob_start();

        echo $tpl->parse($output);

        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    /** Builds all items frame.
     *
     * @param  rootDoc $rootDoc Reference to rootDoc
     * @param  object  $phpapi  phpapi object
     * @return string           Parsed template "all-items"
     */
    private function &allItems(&$rootDoc, $phpapi) {
        $output = [];
        $classes = &$rootDoc->classes();
        if ($classes) {
            ksort($classes);
            foreach ($classes as $name => $class) {
                $output['class'][$name]['path']    = $class->asPath();
                $output['class'][$name]['name']    = $class->name();
                $output['class'][$name]['package'] = $class->packageName();
            }
        }
        $functions = &$rootDoc->functions();
        if ($functions) {
            ksort($functions);
            foreach ($functions as $name => $function) {
                $output['function'][$name]['path']    = $function->asPath();
                $output['function'][$name]['name']    = $function->name();
                $output['function'][$name]['package'] = $function->packageName();
            }
        }
        $globals = &$rootDoc->globals();
        if ($globals) {
            ksort($globals);
            foreach ($globals as $name => $global) {
                $output['global'][$name]['path']    = $global->asPath();
                $output['global'][$name]['name']    = $global->name();
                $output['global'][$name]['package'] = $global->packageName();
            }
        }
        $tpl = new template($phpapi->options['doclet'], 'all-items.tpl');
        ob_start();

        echo $tpl->parse($output);

        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
