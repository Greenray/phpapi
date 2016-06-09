<?php
/**
 * This generates the HTML API documentation for each individual interface and class.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      /system/plain/classItems.php
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @package   plain
 */

class classItems extends classWriter {

    /** Constructor. */
    public function __construct() {}

    /**
     * Builds items of the class.
     *
     * @param  doclet     &$doclet  Reference to the documentation generator
     * @param  packageDoc &$package Reference to the current package
     * @return string               Parsed template "package-items.tpl"
     */
    public static function classItems(&$doclet, &$package, $depth) {
        $output   = [];
        $depth    = str_repeat('../', $depth);
        $tpl      = new template();
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $pack) {
            $output[$name]['path'] = $depth.$pack->path().DS;
            $output[$name]['name'] = $pack->name;
        }
        $tpl->set('packages', $output);
        $tpl->set('current',  $pack->name);

        $classes = $package->classes;
        if ($classes) {
            ksort($classes);
            $output = [];
            foreach ($classes as $name => $class) {
                $output[$name]['path']    = $depth.$class->path();
                $output[$name]['name']    = $class->name;
                $output[$name]['package'] = $class->package;
            }
            $tpl->set('classes', $output);
        }

        $functions = $package->functions;
        if ($functions) {
            ksort($functions);
            $output = [];
            foreach ($functions as $name => $function) {
                $output[$name]['path']    = $depth.$function->path();
                $output[$name]['name']    = $function->name;
                $output[$name]['package'] = $function->package;
            }
            $tpl->set('functions', $output);
        }

        $globals = $package->globals;
        if ($globals) {
            ksort($globals);
            $output = [];
            foreach ($globals as $name => $global) {
                $output[$name]['path']    = $depth.$global->path();
                $output[$name]['name']    = $global->name;
                $output[$name]['package'] = $global->package;
            }
            $tpl->set('globals', $output);
        }

        return $tpl->parse($doclet->rootDoc->phpapi, 'class-items');
    }
}
