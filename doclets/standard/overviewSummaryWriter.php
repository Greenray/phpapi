<?php
# phpapi: The PHP Documentation Creator

/** This class generates the list of all parsed packages.
 *
 * @file      doclets/standard/overviewSummaryWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class overviewSummaryWriter extends htmlWriter {

    /** Build the package index.
     * @param Doclet doclet
     */
    public function overviewSummaryWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $phpapi =& $this->_doclet->phpapi();

        $this->_sections[0] = ['title' => 'Overview', 'selected' => TRUE];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        if ($phpapi->getOption('tree')) $this->_sections[3] = ['title' => 'Tree', 'url' => 'tree.html'];
        $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->_sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->_sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $output = [];
        $output['title'] = $this->_doclet->docTitle();

        $rootDoc  =& $this->_doclet->rootDoc();
        $overview =& $rootDoc->tags('@text');
        $output['description']  = $this->_processInlineTags($overview, TRUE);
        $output['overviewFile'] = basename($phpapi->getOption('overview'));

        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $name => $package) {
            $description =& $package->tags('@text');
            $output['package'][$name]['path'] = $package->asPath().DS;
            $output['package'][$name]['name'] = $package->name();
            $output['package'][$name]['desc'] = strip_tags($this->_processInlineTags($description, TRUE), '<a><b><strong><u><em>');
        }

        $output['overview'] = $this->_processInlineTags($overview);

        $tpl = new template($phpapi->getOption('doclet'), 'overview-summary');

        ob_start();

        echo $tpl->parse($output);

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('overview-summary.html', 'Overview', TRUE);
    }
}
