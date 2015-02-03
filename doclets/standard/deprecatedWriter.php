<?php
# phpapi: The PHP Documentation Creator

/** Generates the deprecated elements index.
 * @file      doclets/standard/deprecatedWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class deprecatedWriter extends HTMLWriter {

    /** Build the deprecated index.
     * @param Doclet doclet
     */
    public function deprecatedWriter(&$doclet) {
        parent::HTMLWriter($doclet);
        #$this->_id = 'definition';
        $rootDoc = & $this->_doclet->rootDoc();

        $this->_sections[0] = ['title' => 'Overview',        'url' => 'overview-summary.html'];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        #$this->_sections[3] = ['title' => 'Use'];
        $this->_sections[4] = ['title' => 'Tree',            'url' => 'overview-tree.html'];
        if ($doclet->includeSource()) {
            $this->_sections[5] = ['title' => 'Files',       'url' => 'overview-files.html'];
        }
        $this->_sections[6] = ['title' => 'Deprecated', 'selected' => TRUE];
        $this->_sections[7] = ['title' => 'Todo',            'url' => 'todo-list.html'];
        $this->_sections[8] = ['title' => 'Index',           'url' => 'index-all.html'];

        $deprecatedClasses = array();
        $classes = & $rootDoc->classes();
        $deprecatedFields = array();
        $deprecatedMethods = array();
        if ($classes) {
            foreach ($classes as $class) {
                if ($class->tags('@deprecated')) {
                    $deprecatedClasses[] = $class;
                }
                $fields = & $class->fields();
                if ($fields) {
                    foreach ($fields as $field) {
                        if ($field->tags('@deprecated')) {
                            $deprecatedFields[] = $field;
                        }
                    }
                }
                $classes = & $class->methods();
                if ($classes) {
                    foreach ($classes as $method) {
                        if ($method->tags('@deprecated')) {
                            $deprecatedMethods[] = $method;
                        }
                    }
                }
            }
        }
        $deprecatedGlobals = array();
        $globals = & $rootDoc->globals();
        if ($globals) {
            foreach ($globals as $global) {
                if ($global->tags('@deprecated')) {
                    $deprecatedGlobals[] = $global;
                }
            }
        }
        $deprecatedFunctions = array();
        $functions = & $rootDoc->functions();
        if ($functions) {
            foreach ($functions as $function) {
                if ($function->tags('@deprecated')) {
                    $deprecatedFunctions[] = $function;
                }
            }
        }

        ob_start();

        echo '<hr>';
        echo '<h1>Deprecated API</h1>';
        echo '<hr>';

        if ($deprecatedClasses || $deprecatedFields || $deprecatedMethods || $deprecatedGlobals || $deprecatedFunctions) {
            echo '<h2>Contents</h2>';
            echo '<ul>';
            if ($deprecatedClasses) {
                echo '<li><a href="#deprecated_class">Deprecated Classes</a></li>';
            }
            if ($deprecatedFields) {
                echo '<li><a href="#deprecated_field">Deprecated Fields</a></li>';
            }
            if ($deprecatedMethods) {
                echo '<li><a href="#deprecated_method">Deprecated Methods</a></li>';
            }
            if ($deprecatedGlobals) {
                echo '<li><a href="#deprecated_global">Deprecated Globals</a></li>';
            }
            if ($deprecatedFunctions) {
                echo '<li><a href="#deprecated_function">Deprecated Functions</a></li>';
            }
            echo '</ul>';
        }

        if ($deprecatedClasses) {
            echo '<table id="deprecated_class" class="detail">';
            echo '<tr><th colspan="2" class="title">Deprecated Classes</th></tr>';
            foreach ($deprecatedClasses as $class) {
                $textTag = & $class->tags('@text');
                echo '<tr><td class="name"><a href="', $class->asPath(), '">', $class->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($textTag)
                    echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                echo '</td></tr>';
            }
            echo '</table>';
        }

        if ($deprecatedFields) {
            echo '<table id="deprecated_field" class="detail">';
            echo '<tr><th colspan="2" class="title">Deprecated Fields</th></tr>';
            foreach ($deprecatedFields as $field) {
                $textTag = & $field->tags('@text');
                echo '<tr>';
                echo '<td class="name"><a href="', $field->asPath(), '">', $field->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($textTag)
                    echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        if ($deprecatedMethods) {
            echo '<table id="deprecated_method" class="detail">';
            echo '<tr><th colspan="2" class="title">Deprecated Methods</th></tr>';
            foreach ($deprecatedMethods as $method) {
                $textTag = & $method->tags('@text');
                echo '<tr>';
                echo '<td class="name"><a href="', $method->asPath(), '">', $method->qualifiedName(), '"</a></td>';
                echo '<td class="description">';
                if ($textTag)
                    echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        if ($deprecatedGlobals) {
            echo '<table id="deprecated_global" class="detail">';
            echo '<tr><th colspan="2" class="title">Deprecated Globals</th></tr>';
            foreach ($deprecatedGlobals as $global) {
                $textTag = & $global->tags('@text');
                echo '<tr>';
                echo '<td class="name"><a href="', $global->asPath(), '">', $global->qualifiedName(), '"</a></td>';
                echo '<td class="description">';
                if ($textTag)
                    echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        if ($deprecatedFunctions) {
            echo '<table id="deprecated_function" class="detail">';
            echo '<tr><th colspan="2" class="title">Deprecated Functions</th></tr>';
            foreach ($deprecatedFunctions as $function) {
                $textTag = & $function->tags('@text');
                echo '<tr>';
                echo '<td class="name"><a href="', $function->asPath(), '">', $function->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($textTag)
                    echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('deprecated-list.html', 'Deprecated', TRUE);
    }
}
