<?php
# phpapi: The PHP Documentation Creator

/** This uses GeSHi to generate formatted source for each source file in the parsed code.
 * @file      doclets/standard/sourceWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class sourceWriter extends HTMLWriter {

    /** Parse the source files.
     * @param Doclet doclet
     */
    public function sourceWriter(&$doclet) {
        parent::HTMLWriter($doclet);
        $rootDoc = & $this->_doclet->rootDoc();
        $phpapi  = & $this->_doclet->phpapi();

        $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        #$this->_sections[3] = ['title' => 'Use'];
        if ($phpapi->getOption('tree')) {
            $this->_sections[4] = ['title' => 'Tree',   'url' => 'overview-tree.html'];
        }
        $this->_sections[5] = ['title' => 'Files',      'url' => 'overview-files.html', 'selected' => TRUE];
        $this->_sections[6] = ['title' => 'Deprecated', 'url' => 'deprecated-list.html'];
        $this->_sections[7] = ['title' => 'Todo',       'url' => 'todo-list.html'];
        $this->_sections[8] = ['title' => 'Index',      'url' => 'index-all.html'];

        $sources   = & $rootDoc->sources();
        $this->_id = 'files';

        ob_start();

        echo '<hr>';
        echo '<h1>Source Files</h1>';
        echo '<ul>';
        foreach ($sources as $filename => $data) {
            $url = strtolower(str_replace(DS, '/', $filename));
            echo '<li><a href="source/', $url, '.html">', $filename, '</a></li>';
        }
        echo '</ul>';
        echo '<hr>';
        $this->_output = ob_get_contents();

        ob_end_clean();

        $this->_write('overview-files.html', 'Overview', TRUE);
        $this->_id = 'file';
        foreach ($sources as $filename => $data) {
            $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Package'];
            $this->_sections[2] = ['title' => 'Class'];
            #$this->_sections[3] = ['title' => 'Use'];
            if ($phpapi->getOption('tree')) {
                $this->_sections[4] = ['title' => 'Tree'];
            }
            $this->_sections[5] = ['title' => 'Files',      'url' => 'overview-files.html'];
            $this->_sections[6] = ['title' => 'Deprecated', 'url' => 'deprecated-list.html'];
            $this->_sections[7] = ['title' => 'Todo',       'url' => 'todo-list.html'];
            $this->_sections[8] = ['title' => 'Index',      'url' => 'index-all.html'];

            $this->_depth = substr_count($filename, '/') + 1;

            if (class_exists('GeSHi')) {
                $geshi = new GeSHi($data[0], 'php');
                $source = $geshi->parse_code();
            } else {
                $source = '<pre>'.$data[0].'</pre>';
            }

            ob_start();

            echo '<hr>';
            echo '<h1>'.$filename.'</h1>';
            if (isset($data[1]['tags']['@text'])) {
                echo '<div class="comment" id="overview_description">', $this->_processInlineTags($data[1]['tags']['@text']), '</div>';
            }
            echo '<hr>';
            foreach (explode("\n", $source) as $index => $line) {
                echo '<a name="line'.($index + 1).'"></a>'.$line;
            }
            $this->_output = ob_get_contents();
            ob_end_clean();

            $this->_write('source/'.strtolower($filename).'.html', $filename, TRUE);
        }
    }
}
