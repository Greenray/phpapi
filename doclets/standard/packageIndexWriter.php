<?php
# phpapi: The PHP Documentation Creator

/** This class generates the overview-summary.html file that lists all parsed packages.
 * @file      doclets/standard/packageIndexWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class packageIndexWriter extends HTMLWriter {

    /** Build the package index.
     * @param Doclet doclet
     */
    public function packageIndexWriter(&$doclet) {
        parent::htmlWriter($doclet);
        $phpapi = & $this->_doclet->phpapi();

        $this->_sections[0] = ['title' => 'Overview', 'selected' => TRUE];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        #$this->_sections[3] = ['title' => 'Use'];
        if ($phpapi->getOption('tree')) {
            $this->_sections[4] = ['title' => 'Tree',   'url' => 'overview-tree.html'];
        }
        if ($doclet->includeSource()) {
            $this->_sections[5] = ['title' => 'Files',  'url' => 'overview-files.html'];
        }
        $this->_sections[6] = ['title' => 'Deprecated', 'url' => 'deprecated-list.html'];
        $this->_sections[7] = ['title' => 'Todo',       'url' => 'todo-list.html'];
        $this->_sections[8] = ['title' => 'Index',      'url' => 'index-all.html'];

        ob_start();

        echo '<hr>';
        echo '<h1>'.$this->_doclet->docTitle().'</h1>';

        $rootDoc = & $this->_doclet->rootDoc();
        $textTag = & $rootDoc->tags('@text');
        if ($textTag) {
            $description = $this->_processInlineTags($textTag, TRUE);
            if ($description) {
                echo '<div class="comment">', $description, '</div>';
                echo '<dl><dt>See:</dt><dd><b><a href="#overview_description">Description</a></b></dd></dl>';
            }
        }

        echo '<table class="title">';
        echo '<tr><th colspan="2" class="title">Namespaces</th></tr>';
        $packages = & $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $name => $package) {
            $textTag = & $package->tags('@text');
            echo '<tr><td class="name"><a href="'.$package->asPath().'/package-summary.html">'.$package->name().'</a></td>';
            echo '<td class="description">'.strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>').'</td></tr>';
        }
        echo '</table>';

        $textTag = & $rootDoc->tags('@text');
        if ($textTag) {
            $description = $this->_processInlineTags($textTag);
            if ($description) {
                echo '<div class="comment" id="overview_description">', $description, '</div>';
            }
        }

        echo '<hr>';

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('overview-summary.html', 'Overview', TRUE);
    }
}
