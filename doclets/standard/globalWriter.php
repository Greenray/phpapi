<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each global variable.
 * @file      doclets/standard/globalWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
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
        $rootDoc  = & $this->_doclet->rootDoc();
        $packages = & $rootDoc->packages();
        ksort($packages);

        foreach ($packages as $packageName => $package) {

            $this->_sections[0] = ['title' => 'Overview',    'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',   'url' => $package->asPath().'/package-summary.html'];
            $this->_sections[2] = ['title' => 'Global', 'selected' => TRUE];
            #$this->_sections[3] = ['title' => 'Use'];
            $this->_sections[4] = ['title' => 'Tree',        'url' => 'overview-tree.html'];
            if ($doclet->includeSource()) {
                $this->_sections[5] = ['title' => 'Files',   'url' => 'overview-files.html'];
            }
            $this->_sections[6] = ['title' => 'Deprecated',  'url' => 'deprecated-list.html'];
            $this->_sections[7] = ['title' => 'Todo',        'url' => 'todo-list.html'];
            $this->_sections[8] = ['title' => 'Index',       'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            ob_start();

            echo '<hr>';
            echo '<h1>Globals</h1>';
            echo '<hr>';

            $globals = & $package->globals();

            if ($globals) {
                ksort($globals);
                echo '<table id="summary_global" class="title">';
                echo '<tr><th colspan="2" class="title">Global Summary</th></tr>';
                foreach ($globals as $global) {
                    $textTag = & $global->tags('@text');
                    $type = & $global->type();
                    echo '<tr>';
                    echo '<td class="type">', $global->modifiers(FALSE), ' ', $global->typeAsString(), '</td>';
                    echo '<td class="description">';
                    echo '<p class="name"><a href="#', $global->name(), '">', $global->name(), '</a></p>';
                    if ($textTag) {
                        echo '<p class="description">', strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>'), '</p>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';

                echo '<h2 id="detail_global">Global Detail</h2>';
                foreach ($globals as $global) {
                    $textTag = & $global->tags('@text');
                    $type = & $global->type();
                    $this->_sourceLocation($global);
                    echo '<h3 id="', $global->name(), '">', $global->name(), '</h3>';
                    echo '<code class="signature">', $global->modifiers(), ' ', $global->typeAsString(), ' <strong>';
                    echo $global->name(), '</strong>';
                    if ($global->value())
                        echo ' = ', htmlspecialchars($global->value());
                    echo '</code>';
                    echo '<div class="details">';
                    if ($textTag) {
                        echo $this->_processInlineTags($textTag);
                    }
                    echo '</div>';
                    $this->_processTags($global->tags());
                    echo '<hr>';
                }
            }

            $this->_output = ob_get_contents();
            ob_end_clean();

            $this->_write($package->asPath().'/package-globals.html', 'Globals', TRUE);
        }
    }

}
