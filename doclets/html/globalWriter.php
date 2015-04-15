<?php
/** This generates the HTML API documentation for each global variable.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/globalWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 */

class globalWriter extends htmlWriter {

    /** Build the function definitons.
     * @param object &$doclet The reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $this->id = 'globals';
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->sections[0] = ['title' => 'Overview',    'url' => $index.'.html'];
            $this->sections[1] = ['title' => 'Namespace',   'url' => $package->asPath().DS.'package-summary.html'];
            $this->sections[2] = ['title' => 'Global', 'selected' => TRUE];
            $this->sections[3] = ['title' => $package->name.'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated',  'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',        'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',       'url' => 'index-all.html'];

            $this->depth = $package->depth() + 1;

            if (empty($package->classes)) $this->sections[3] = ['title' => 'Tree'];

            $output  = [];
            $globals = &$package->globals;
            if ($globals) {
                ksort($globals);
                $output['package'] = $package->name;
                $output['global']  = $this->showObject($globals);

                $this->items = $this->packageItems($doclet->rootDoc->phpapi, $package, $this->depth);

                $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'globals.tpl');
                ob_start();

                echo $tpl->parse($output);

                $this->output = ob_get_contents();
                ob_end_clean();
                $this->write($package->asPath().DS.'package-globals.html', __('Глобальные элементы'));
            }
        }
    }
}
