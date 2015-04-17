<?php
/** This generates the package-frame.html file that lists the interfaces and
 * classes in a given package for displaying in the lower-left frame of the
 * frame-formatted default output.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/frames/packageFrameWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   frames
 */

class packageFrameWriter extends htmlWriter {

    /** Build the package frame index.
     * @param object &$doclet The reference to the documentation generator
     */
    public function __construct(&$doclet) {
        parent::htmlWriter($doclet);

        $all = [];
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $this->depth  = $package->depth() + 1;
            $packpath  = $package->asPath().DS;
            $prefix  = str_repeat('../', $this->depth);
            $output  = [];
            $output['name'] = $package->name;

            $classes = &$package->ordinaryClasses();
            if ($classes) {
                foreach ($classes as $i => $class) {
                    $output['class'][$i]['name']     = $class->name;
                    $output['class'][$i]['clpath']   = $class->asPath();
                    $output['class'][$i]['frpath']   = $prefix.$output['class'][$i]['clpath'];
                    $output['class'][$i]['packpath'] = $packpath;
                    $output['class'][$i]['packname'] = $package->name;
                }
            }

            $interfaces = &$package->interfaces();
            if ($interfaces) {
                ksort($interfaces);
                foreach ($interfaces as $i => $interface) {
                    $output['interface'][$i]['name']     = $interface->name;
                    $output['interface'][$i]['intpath']  = $interface->asPath();
                    $output['interface'][$i]['frpath']   = $prefix.$output['interface'][$i]['intpath'];
                    $output['interface'][$i]['packpath'] = $packpath;
                    $output['interface'][$i]['packname'] = $package->name;
                }
            }

            $traits = &$package->traits();
            if ($traits) {
                ksort($traits);
                foreach ($traits as $i => $trait) {
                    $output['trait'][$i]['name']     = $trait->name;
                    $output['trait'][$i]['trpath']   = $trait->asPath();
                    $output['trait'][$i]['frpath']   = $prefix.$output['trait'][$i]['trpath'];
                    $output['trait'][$i]['packpath'] = $packpath;
                    $output['trait'][$i]['packname'] = $package->name;
                }
            }

            $exceptions = &$package->exceptions();
            if ($exceptions) {
                ksort($exceptions);
                foreach ($exceptions as $i => $exception) {
                    $output['exception'][$i]['name']     = $exception->name;
                    $output['exception'][$i]['expath']   = $exception->asPath();
                    $output['exception'][$i]['frpath']   = $prefix.$output['exception'][$i]['expath'];
                    $output['exception'][$i]['packpath'] = $packpath;
                    $output['exception'][$i]['packname'] = $package->name;
                }
            }

            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                foreach ($functions as $i => $function) {
                    $output['function'][$i]['name']     = $function->name;
                    $output['function'][$i]['funpath']  = $function->asPath();
                    $output['function'][$i]['frpath']   = $prefix.$output['function'][$i]['funpath'];
                    $output['function'][$i]['packpath'] = $packpath;
                    $output['function'][$i]['packname'] = $package->name;
                }
            }

            $globals = &$package->globals;
            if ($globals) {
                ksort($globals);
                foreach ($globals as $i => $global) {
                    $output['global'][$i]['name']   = $global->name;
                    $output['global'][$i]['glpath'] = $global->asPath();
                    $output['global'][$i]['frpath'] = $prefix.$output['global'][$i]['glpath'];
                }
            }

            $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'package-frame.tpl');
            ob_start();

            echo $tpl->parse($output);

            $this->output = ob_get_contents();
            ob_end_clean();
            $this->write($package->asPath().DS.'package-frame.html', $package->name, FALSE);
            unset($output['name']);
            $all = array_merge_recursive($all, $output);
        }

        $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'all-items.tpl');
        ob_start();

        echo $tpl->parse($all);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('all-items.html', __('Полный список'), FALSE);
    }
}
