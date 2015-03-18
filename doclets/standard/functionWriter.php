<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each global function.
 *
 * @file      doclets/standard/functionWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class functionWriter extends htmlWriter {

    /** Build the function definitons.
     * @param Doclet doclet
     */
    public function functionWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->_id = 'definition';
        $rootDoc   =& $this->_doclet->rootDoc();
        $phpapi    =& $this->_doclet->phpapi();
        $packages  =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_sections[0] = ['title' => 'Overview',      'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',     'url' => $package->asPath().'/package-summary.html'];
            $this->_sections[2] = ['title' => 'Function', 'selected' => TRUE];
            $this->_sections[4] = ['title' => 'Tree',          'url' => $package->asPath().'/package-tree.html'];
            $this->_sections[6] = ['title' => 'Deprecated',    'url' => 'deprecated-list.html'];
            $this->_sections[7] = ['title' => 'Todo',          'url' => 'todo-list.html'];
            $this->_sections[8] = ['title' => 'Index',         'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            ob_start();

            $output = [];

            $functions =& $package->functions();
            if ($functions) {
                ksort($functions);
                $output['function']  = $this->showObject($functions, FALSE);
                $output['functions'] = $this->showObject($functions);
            }

            $tpl = new template($phpapi->getOption('doclet'), 'functions');
            echo $tpl->parse($output);

            $this->_output = ob_get_contents();
            ob_end_clean();

            $this->_write($package->asPath().'/package-functions.html', 'Functions', TRUE);
        }
    }
}
