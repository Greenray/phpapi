<?php
/**
 * Generates the "items" section of the API documentation page.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      doclets/html/plain/items.php
 * @version   4.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   plain
 */

class items extends htmlWriter {

    /** Constructor. */
    public function __construct() {}

    /**
     * Builds all items section.
     *
     * @param  phpapi &$phpapi Reference to the application object
     * @param  object &$doclet Reference to the documentation generator
     * @param  string $path    Path to directory for output
     * @return string          Parsed template "items.tpl"
     */
    public static function items(&$phpapi, &$doclet, $path) {
        $output   = [];
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $packagePath = $path.$package->asPath().DS;
            $output['package'][$name]['path'] = $packagePath;
            $output['package'][$name]['name'] = $package->name;

            $classes = &$package->ordinaryClasses();
            if ($classes) {
                foreach ($classes as $i => $class) {
                    $output['class'][$i]['path']     = $path.$class->asPath();
                    $output['class'][$i]['name']     = $class->name;
                    $output['class'][$i]['packpath'] = $packagePath;
                    $output['class'][$i]['packname'] = $class->package;
                }
            }

            $interfaces = &$package->interfaces();
            if ($interfaces && is_array($interfaces)) {
                ksort($interfaces);
                foreach ($interfaces as $i => $interface) {
                    $output['interface'][$i]['path']     = $path.$interface->asPath();
                    $output['interface'][$i]['name']     = $interface->name;
                    $output['interface'][$i]['packpath'] = $packagePath;
                    $output['interface'][$i]['packname'] = $interface->package;
                }
            }

            $traits = &$package->traits();
            if ($traits && is_array($traits)) {
                ksort($traits);
                foreach ($traits as $i => $trait) {
                    $output['trait'][$i]['path']     = $path.$trait->asPath();
                    $output['trait'][$i]['name']     = $trait->name;
                    $output['trait'][$i]['packpath'] = $packagePath;
                    $output['trait'][$i]['packname'] = $trait->package;
                }
            }

            $exceptions = &$package->exceptions();
            if ($exceptions && is_array($exceptions)) {
                ksort($exceptions);
                foreach ($exceptions as $i => $exception) {
                    $output['exception'][$i]['path']     = $path.$exception->asPath();
                    $output['exception'][$i]['name']     = $exception->name;
                    $output['exception'][$i]['packpath'] = $packagePath;
                    $output['exception'][$i]['packname'] = $exception->package;
                }
            }

            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                foreach ($functions as $i => $function) {
                    $output['function'][$i]['path']     = $path.$function->asPath();
                    $output['function'][$i]['name']     = $function->name;
                    $output['function'][$i]['packpath'] = $packagePath;
                    $output['function'][$i]['packname'] = $function->package;
                }
            }

            $globals = &$package->globals;
            if ($globals) {
                ksort($globals);
                foreach ($globals as $i => $global) {
                    $output['global'][$i]['path'] = $path.$global->asPath();
                    $output['global'][$i]['name'] = $global->name;
                }
            }
        }

        $tpl   = new template($phpapi->options['doclet'], 'items.tpl');
        $items = $tpl->parse($output);
        return $items;
    }
}
