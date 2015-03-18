<?php
# phpapi: The PHP Documentation Creator

/** This class generates the list of all parsed packages.
 *
 * @file      doclets/standard/packageIndexWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class packageIndexWriter extends htmlWriter {

    /** Build the package index.
     * @param Doclet doclet
     */
    public function packageIndexWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $phpapi =& $this->_doclet->phpapi();

        $this->_sections[0] = ['title' => 'Overview', 'selected' => TRUE];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        if ($phpapi->getOption('tree')) {
            $this->_sections[3] = ['title' => 'Tree',   'url' => 'overview-tree.html'];
        }
        $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->_sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->_sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        ob_start();

        $output = [];
        $output['title'] = $this->_doclet->docTitle();

        $rootDoc =& $this->_doclet->rootDoc();
        $textTag =& $rootDoc->tags('@text');
        if ($textTag) {
            $description = $this->_processInlineTags($textTag, TRUE);
            if ($description) {
                $output['description'] = $description;
            }
        }

        $packages =& $rootDoc->packages();
        ksort($packages);
        $packs = [];
        foreach ($packages as $name => $package) {
            $textTag =& $package->tags('@text');
            $packs[$name]['path'] = $package->asPath();
            $packs[$name]['name'] = $package->name();
            $packs[$name]['tags'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
        }
        $output['package'] = $packs;
        $textTag =& $rootDoc->tags('@text');
        if ($textTag) {
            $description = $this->_processInlineTags($textTag);
            if ($description) $output['overview'] = $description;
        }

        $tpl = new template($phpapi->getOption('doclet'), 'package-index');
        echo $tpl->parse($output);

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('overview-summary.html', 'Overview', TRUE);
    }
}
