<?php
/**
 * This generates the HTML API documentation for each global function.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2016 Victor Nabatov
 * @license   CreatiCreative Commons Attribution-ShareAlike 4.0 International
 * @file      doclets/html/functionWriter.php
 * @package   html
 */

class functionWriter extends htmlWriter {

    /**
     * Builds the function definitons.
     *
     * @param doclet &$doclet Reference to the documentation generator
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
            $this->sections[3] = ['title' => $package->name.'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated',    'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',          'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',         'url' => 'index-all.html'];

            $this->depth = $package->depth() + 1;

            if (empty($package->classes)) $this->sections[3] = ['title' => 'Tree'];

            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                $tpl = new template();
                $tpl->set('package',   $package->name);
                $tpl->set('functions', $this->showObject($functions));

                if ($doclet->rootDoc->phpapi->options['doclet'] === 'plain') {
                    $this->items = $this->packageItems($doclet->rootDoc->phpapi, $package, $this->depth);
                }
                $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'functions');
                $this->write($package->asPath().DS.'package-functions.html', __('Functions'));
            }
        }
    }
}
