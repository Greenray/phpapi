<?php
/** This generates the HTML API documentation for each global function.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/functionWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 */

class functionWriter extends htmlWriter {

    /** Build the function definitons.
     * @param object &$doclet The reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $this->id = 'functions';
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->sections[0] = ['title' => 'Overview',      'url' => $index.'.html'];
            $this->sections[1] = ['title' => 'Namespace',     'url' => $package->asPath().DS.'package-summary.html'];
            $this->sections[2] = ['title' => 'Function', 'selected' => TRUE];
            $this->sections[4] = ['title' => $package->name.'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[6] = ['title' => 'Deprecated',    'url' => 'deprecated.html'];
            $this->sections[7] = ['title' => 'Todo',          'url' => 'todo.html'];
            $this->sections[8] = ['title' => 'Index',         'url' => 'index-all.html'];

            $this->depth = $package->depth() + 1;

            $output    = [];
            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                $output['package']  = $package->name;
                $output['function'] = $this->showObject($functions);

                $this->items = $this->packageItems($doclet->rootDoc->phpapi, $package, $this->depth);

                $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'functions.tpl');
                ob_start();

                echo $tpl->parse($output);

                $this->output = ob_get_contents();
                ob_end_clean();
                $this->write($package->asPath().DS.'package-functions.html', __('Функции'));
            }
        }
    }
}