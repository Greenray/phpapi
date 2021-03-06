<?php
/**
 * Generates the "items" section of the API documentation page.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      /system/plain/items.php
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @package   plain
 */

class items extends htmlWriter {

    /** Constructor. */
    public function __construct() {}

    /**
     * Builds all items section.
     *
     * @param  phpapi &$phpapi Reference to the application object
     * @param  doclet &$doclet Reference to the documentation generator
     * @param  string $path    Path to directory for output
     * @return string          Parsed template "items.tpl"
     */
    public static function items(&$phpapi, &$doclet, $path) {
        $output   = $pack = $all = [];
        $tpl      = new template();
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $packagePath = $path.$package->path().DS;
            $output['packages'][$name]['path'] = $packagePath;
            $output['packages'][$name]['name'] = $package->name;

            $interfaces = &$package->interfaces();
            if ($interfaces && is_array($interfaces)) {
                ksort($interfaces);
                foreach ($interfaces as $i => $interface) {
                    $output['interfaces'][$i]['path']     = $path.$interface->path();
                    $output['interfaces'][$i]['name']     = $interface->name;
                    $output['interfaces'][$i]['packpath'] = $packagePath;
                    $output['interfaces'][$i]['packname'] = $interface->package;
                }
            }

            $traits = &$package->traits();
            if ($traits && is_array($traits)) {
                ksort($traits);
                foreach ($traits as $i => $trait) {
                    $output['traits'][$i]['path']     = $path.$trait->path();
                    $output['traits'][$i]['name']     = $trait->name;
                    $output['traits'][$i]['packpath'] = $packagePath;
                    $output['traits'][$i]['packname'] = $trait->package;
                }
            }

            $classes = &$package->ordinaryClasses();
            if ($classes) {
                foreach ($classes as $i => $class) {
                    $output['classes'][$i]['path']     = $path.$class->path();
                    $output['classes'][$i]['name']     = $class->name;
                    $output['classes'][$i]['packpath'] = $packagePath;
                    $output['classes'][$i]['packname'] = $class->package;
                }
            }

            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                foreach ($functions as $i => $function) {
                    $output['functions'][$i]['path']     = $path.$function->path();
                    $output['functions'][$i]['name']     = $function->name;
                    $output['functions'][$i]['packpath'] = $packagePath;
                    $output['functions'][$i]['packname'] = $function->package;
                }
            }

            $globals = &$package->globals;
            if ($globals) {
                ksort($globals);
                foreach ($globals as $i => $global) {
                    $output['globals'][$i]['path'] = $path.$global->path();
                    $output['globals'][$i]['name'] = $global->name;
                }
            }

            $exceptions = &$package->exceptions();
            if ($exceptions && is_array($exceptions)) {
                ksort($exceptions);
                foreach ($exceptions as $i => $exception) {
                    $output['exceptions'][$i]['path']     = $path.$exception->path();
                    $output['exceptions'][$i]['name']     = $exception->name;
                    $output['exceptions'][$i]['packpath'] = $packagePath;
                    $output['exceptions'][$i]['packname'] = $exception->package;
                }
            }
        }

        $tpl->set($output);
        return $tpl->parse($phpapi, 'items');
    }
}
