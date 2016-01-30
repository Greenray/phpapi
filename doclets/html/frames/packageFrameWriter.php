<?php
/**
 * This generates the package-frame.html file that lists the interfaces and
 * classes in a given package for displaying in the lower-left frame of the
 * frame-formatted default output.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      doclets/html/frames/packageFrameWriter.php
 * @package   frames
 */

class packageFrameWriter extends htmlWriter {

    /**
     * Builds the package frame index.
     *
     * @param doclet &$doclet Reference to the documentation generator
     */
    public function __construct(&$doclet) {
        parent::htmlWriter($doclet);

        $all = [];
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $packpath    = $package->asPath().DS;
            $this->depth = $package->depth() + 1;
            $path = str_repeat('../', $this->depth);

            $tpl = new template();
            $tpl->set('package', $package->name);
            $classes = &$package->ordinaryClasses();
            if ($classes) {
                $output = [];
                foreach ($classes as $i => $class) {
                    $output['classes'][$i]['name']     = $class->name;
                    $output['classes'][$i]['path']     = $class->asPath();
                    $output['classes'][$i]['allpath']  = $path.$output['classes'][$i]['path'];
                    $output['classes'][$i]['packpath'] = $packpath;
                    $output['classes'][$i]['packname'] = $package->name;
                }
                $tpl->set('classes', $output['classes']);
                $all = array_merge_recursive($all, $output);
            }

            $interfaces = &$package->interfaces();
            if ($interfaces) {
                ksort($interfaces);
                $output = [];
                foreach ($interfaces as $i => $interface) {
                    $output['interfaces'][$i]['name']     = $interface->name;
                    $output['interfaces'][$i]['path']     = $interface->asPath();
                    $output['interfaces'][$i]['allpath']  = $path.$output['interfaces'][$i]['path'];
                    $output['interfaces'][$i]['packpath'] = $packpath;
                    $output['interfaces'][$i]['packname'] = $package->name;
                }
                $tpl->set('interfaces', $output['interfaces']);
                $all = array_merge_recursive($all, $output);
            }

            $traits = &$package->traits();
            if ($traits) {
                ksort($traits);
                $output = [];
                foreach ($traits as $i => $trait) {
                    $output['traits'][$i]['name']     = $trait->name;
                    $output['traits'][$i]['path']     = $trait->asPath();
                    $output['traits'][$i]['allpath']  = $path.$output['traits'][$i]['path'];
                    $output['traits'][$i]['packpath'] = $packpath;
                    $output['traits'][$i]['packname'] = $package->name;
                }
                $tpl->set('traits', $output['traits']);
                $all = array_merge_recursive($all, $output);
            }

            $exceptions = &$package->exceptions();
            if ($exceptions) {
                ksort($exceptions);
                $output = [];
                foreach ($exceptions as $i => $exception) {
                    $output['exceptions'][$i]['name']     = $exception->name;
                    $output['exceptions'][$i]['path']     = $exception->asPath();
                    $output['exceptions'][$i]['allpath']  = $path.$output['exceptions'][$i]['path'];
                    $output['exceptions'][$i]['packpath'] = $packpath;
                    $output['exceptions'][$i]['packname'] = $package->name;
                }
                $tpl->set('exceptions', $output['exceptions']);
                $all = array_merge_recursive($all, $output);
            }

            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                $output = [];
                foreach ($functions as $i => $function) {
                    $output['functions'][$i]['name']     = $function->name;
                    $output['functions'][$i]['path']     = $function->asPath();
                    $output['functions'][$i]['allpath']  = $path.$output['functions'][$i]['path'];
                    $output['functions'][$i]['packpath'] = $packpath;
                    $output['functions'][$i]['packname'] = $package->name;
                }
                $tpl->set('functions', $output['functions']);
                $all = array_merge_recursive($all, $output);
            }

            $globals = &$package->globals;
            if ($globals) {
                ksort($globals);
                $output = [];
                foreach ($globals as $i => $global) {
                    $output['globals'][$i]['name'] = $global->name;
                    $output['globals'][$i]['path'] = $global->asPath();
                    $output['globals'][$i]['allpath']  = $path.$output['globals'][$i]['path'];
                }
                $tpl->set('globals', $output['globals']);
                $all = array_merge_recursive($all, $output);
            }

            $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'package-frame');
            $this->write($package->asPath().DS.'package-frame.html', $package->name, FALSE);
        }

        $tpl = new template();
        $tpl->set($all);
        $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'all-items');
        $this->write('all-items.html', __('All items'), FALSE);
    }
}
