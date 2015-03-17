<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each global variable.
 *
 * @file      doclets/standard/globalWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class globalWriter extends HTMLWriter {

    /** Build the function definitons.
     * @param Doclet doclet
     */
    public function globalWriter(&$doclet) {
        parent::HTMLWriter($doclet);
        $this->_id = 'definition';
        $rootDoc  =& $this->_doclet->rootDoc();
        $phpapi   =& $this->_doclet->phpapi();
        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_sections[0] = ['title' => 'Overview',    'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',   'url' => $package->asPath().'/package-summary.html'];
            $this->_sections[2] = ['title' => 'Global', 'selected' => TRUE];
            $this->_sections[4] = ['title' => 'Tree',        'url' => 'overview-tree.html'];
            $this->_sections[6] = ['title' => 'Deprecated',  'url' => 'deprecated-list.html'];
            $this->_sections[7] = ['title' => 'Todo',        'url' => 'todo-list.html'];
            $this->_sections[8] = ['title' => 'Index',       'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            ob_start();

            $output = [];
            $globals =& $package->globals();
            if ($globals) {
                ksort($globals);
                $output['global'] = $this->showObject($globals, FALSE);
                $output['globals'] = $this->showObject($globals);
            }

            $tpl = new template($phpapi->getOption('doclet'), 'globals');
            echo $tpl->parse($output);

            $this->_output = ob_get_contents();
            ob_end_clean();

            $this->_write($package->asPath().'/package-globals.html', 'Globals', TRUE);
        }
    }
}
