<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each global variable.
 *
 * @file      doclets/standard/globalWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class globalWriter extends htmlWriter {

    /** Build the function definitons.
     * @param Doclet doclet
     */
    public function globalWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->_id = 'definition';
        $rootDoc  =& $this->_doclet->rootDoc();
        $phpapi   =& $this->_doclet->phpapi();
        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_sections[0] = ['title' => 'Overview',    'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',   'url' => $package->asPath().DS.'package-summary.html'];
            $this->_sections[2] = ['title' => 'Global', 'selected' => TRUE];
            $this->_sections[3] = ['title' => 'Tree',        'url' => 'tree.html'];
            $this->_sections[4] = ['title' => 'Deprecated',  'url' => 'deprecated.html'];
            $this->_sections[5] = ['title' => 'Todo',        'url' => 'todo.html'];
            $this->_sections[6] = ['title' => 'Index',       'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            ob_start();

            $output = [];
            $globals =& $package->globals();
            if ($globals) {
                ksort($globals);
                $output['global']  = $this->showObject($globals, FALSE);
                $output['globals'] = $this->showObject($globals);
            }

            $tpl = new template($phpapi->getOption('doclet'), 'globals');
            echo $tpl->parse($output);

            $this->_output = ob_get_contents();
            ob_end_clean();

            $this->_write($package->asPath().DS.'package-globals.html', 'Globals', TRUE);
        }
    }
}
