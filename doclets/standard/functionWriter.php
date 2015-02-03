<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each global function.
 * @file      doclets/standard/functionWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class functionWriter extends HTMLWriter {

    /** Build the function definitons.
     * @param Doclet doclet
     */
    public function functionWriter(&$doclet) {
        parent::HTMLWriter($doclet);
        $this->_id = 'definition';
        $rootDoc   = & $this->_doclet->rootDoc();
        $packages  = & $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_sections[0] = ['title' => 'Overview',      'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',     'url' => $package->asPath().'/package-summary.html'];
            $this->_sections[2] = ['title' => 'Function', 'selected' => TRUE];
            #$this->_sections[3] = ['title' => 'Use'];
            $this->_sections[4] = ['title' => 'Tree', 'url' => $package->asPath().'/package-tree.html'];
            if ($doclet->includeSource()) {
                $this->_sections[5] = ['title' => 'Files',     'url' => 'overview-files.html'];
            }
            $this->_sections[6] = ['title' => 'Deprecated',    'url' => 'deprecated-list.html'];
            $this->_sections[7] = ['title' => 'Todo',          'url' => 'todo-list.html'];
            $this->_sections[8] = ['title' => 'Index',         'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            ob_start();

            echo '<hr>';
            echo '<h1>Functions</h1>';
            echo '<hr>';
            $functions = & $package->functions();
            if ($functions) {
                ksort($functions);
                echo '<table id="summary_function" class="title">';
                echo '<tr><th colspan="2" class="title">Function Summary</th></tr>';
                foreach ($functions as $function) {
                    $textTag = & $function->tags('@text');
                    echo '<tr>';
                    echo '<td class="type">', $function->modifiers(FALSE), ' ', $function->returnTypeAsString(), '</td>';
                    echo '<td class="description">';
                    echo '<p class="name"><a href="#', $function->name(), '()">', $function->name(), '</a>', $function->flatSignature(), '</p>';
                    if ($textTag) {
                        echo '<p class="description">', strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>'), '</p>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';

                echo '<h2 id="detail_function">Function Detail</h2>';
                foreach ($functions as $function) {
                    $textTag = & $function->tags('@text');
                    $this->_sourceLocation($function);
                    echo '<h3 id="', $function->name(), '">', $function->name(), '</h3>';
                    echo '<code class="signature">', $function->modifiers(), ' ', $function->returnTypeAsString(), ' <strong>'.$function->name().'</strong>';
                    echo $function->flatSignature();
                    echo '</code>';
                    echo '<div class="details">';
                    if ($textTag) {
                        echo $this->_processInlineTags($textTag);
                    }
                    $this->_processTags($function->tags());
                    echo '</div>';
                    echo '<hr>';
                }
            }
            $this->_output = ob_get_contents();
            ob_end_clean();

            $this->_write($package->asPath().'/package-functions.html', 'Functions', TRUE);
        }
    }
}
