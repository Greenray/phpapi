<?php
/** This generates the HTML API documentation for each individual interface and class.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/plain/classItems.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   plain
 */

class classItems extends classWriter {

    /** Constructor. */
    public function __construct() {}

    /** Builds items of the class.
     * @param  object     &$doclet  The reference to the documentation generator
     * @param  packageDoc &$package The reference to the current package
     * @return string               Parsed template "class-items.tpl"
     */
    public static function classItems(&$doclet, &$package, $depth) {
        $output  = [];

        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $pack) {
            $output['package'][$name]['path'] = str_repeat('../', $depth).$pack->asPath().DS;
            $output['package'][$name]['name'] = $pack->name;
        }

        $output['current'] = $package->name;
        $classes = $package->classes;

        if ($classes) {
            ksort($classes);
            foreach ($classes as $name => $class) {
                $output['class'][$name]['path']    = str_repeat('../', $depth).$class->asPath();
                $output['class'][$name]['name']    = $class->name;
                $output['class'][$name]['package'] = $class->package;
            }
        }

        $functions = $package->functions;
        if ($functions) {
            ksort($functions);
            foreach ($functions as $name => $function) {
                $output['function'][$name]['path']    = str_repeat('../', $depth).$function->asPath();
                $output['function'][$name]['name']    = $function->name;
                $output['function'][$name]['package'] = $function->package;
            }
        }

        $globals = $package->globals;
        if ($globals) {
            ksort($globals);
            foreach ($globals as $name => $global) {
                $output['global'][$name]['path']    = str_repeat('../', $depth).$global->asPath();
                $output['global'][$name]['name']    = $global->name;
                $output['global'][$name]['package'] = $global->package;
            }
        }
        $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'class-items.tpl');
        return $tpl->parse($output);
    }
}
