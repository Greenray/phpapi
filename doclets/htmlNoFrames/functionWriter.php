<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each global function.
 *
 * @file      doclets/htmlNoFrames/functionWriter.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
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
            $this->_sections[0] = ['title' => 'Overview',      'url' => 'index.html'];
            $this->_sections[1] = ['title' => 'Namespace',     'url' => $package->asPath().DS.'package-summary.html'];
            $this->_sections[2] = ['title' => 'Function', 'selected' => TRUE];
            $this->_sections[4] = ['title' => $package->name().'\Tree',          'url' => $package->asPath().DS.'package-tree.html'];
            $this->_sections[6] = ['title' => 'Deprecated',    'url' => 'deprecated.html'];
            $this->_sections[7] = ['title' => 'Todo',          'url' => 'todo.html'];
            $this->_sections[8] = ['title' => 'Index',         'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            $output    = [];
            $functions =& $package->functions();
            if ($functions) {
                ksort($functions);
                $output['functions'] = $this->showObject($functions);
            }
            $tpl = new template($phpapi->getOption('doclet'), 'functions.tpl');
            ob_start();

            echo $tpl->parse($output);

            $this->_output = ob_get_contents();
            ob_end_clean();
            $this->_write($package->asPath().DS.'package-functions.html', 'Functions');
        }
    }
}
