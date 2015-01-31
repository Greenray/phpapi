<?php
# PhpAPI: The PHP Documentation Creator

/** This generates the todo elements index.
 * @file      doclets/standard/todoWriter.php
 * @version   1.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Standard
 */

class todoWriter extends HTMLWriter {

    /** Build the todo index.
     * @param Doclet doclet
     */
    public function todoWriter(&$doclet) {
        parent::HTMLWriter($doclet);
        $rootDoc = & $this->_doclet->rootDoc();

        $this->_sections[0] = ['title' => 'Overview',    'url' => 'overview-summary.html'];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        #$this->_sections[3] = ['title' => 'Use'];
        $this->_sections[4] = ['title' => 'Tree',        'url' => 'overview-tree.html'];
        if ($doclet->includeSource())
            $this->_sections[5] = ['title' => 'Files',   'url' => 'overview-files.html'];
        $this->_sections[6] = ['title' => 'Deprecated',  'url' => 'deprecated-list.html'];
        $this->_sections[7] = ['title' => 'Todo',   'selected' => TRUE];
        $this->_sections[8] = ['title' => 'Index',       'url' => 'index-all.html'];

        $todoClasses = [];
        $classes     = & $rootDoc->classes();
        $todoFields  = [];
        $todoMethods = [];
        if ($classes) {
            foreach ($classes as $class) {
                if ($class->tags('@todo')) {
                    $todoClasses[] = $class;
                }
                $fields = & $class->fields();
                if ($fields) {
                    foreach ($fields as $field) {
                        if ($field->tags('@todo')) {
                            $todoFields[] = $field;
                        }
                    }
                }
                $classes = & $class->methods();
                if ($classes) {
                    foreach ($classes as $method) {
                        if ($method->tags('@todo')) {
                            $todoMethods[] = $method;
                        }
                    }
                }
            }
        }
        $todoGlobals = [];
        $globals     = & $rootDoc->globals();
        if ($globals) {
            foreach ($globals as $global) {
                if ($global->tags('@todo')) {
                    $todoGlobals[] = $global;
                }
            }
        }
        $todoFunctions = [];
        $functions     = & $rootDoc->functions();
        if ($functions) {
            foreach ($functions as $function) {
                if ($function->tags('@todo')) {
                    $todoFunctions[] = $function;
                }
            }
        }

        ob_start();

        echo '<hr>';
        echo '<h1>Todo</h1>';
        echo '<hr>';

        if ($todoClasses || $todoFields || $todoMethods || $todoGlobals || $todoFunctions) {
            echo '<h2>Contents</h2>';
            echo '<ul>';
            if ($todoClasses) {
                echo '<li><a href="#todo_class">Todo Classes</a></li>';
            }
            if ($todoFields) {
                echo '<li><a href="#todo_field">Todo Fields</a></li>';
            }
            if ($todoMethods) {
                echo '<li><a href="#todo_method">Todo Methods</a></li>';
            }
            if ($todoGlobals) {
                echo '<li><a href="#todo_global">Todo Globals</a></li>';
            }
            if ($todoFunctions) {
                echo '<li><a href="#todo_function">Todo Functions</a></li>';
            }
            echo '</ul>';
        }

        if ($todoClasses) {
            echo '<table id="todo_class" class="detail">';
            echo '<tr><th colspan="2" class="title">Todo Classes</th></tr>';
            foreach ($todoClasses as $class) {
                $todoTag = & $class->tags('@todo');
                echo '<tr><td class="name"><a href="', $class->asPath(), '">', $class->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($todoTag)
                    echo strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                echo '</td></tr>';
            }
            echo '</table>';
        }

        if ($todoFields) {
            echo '<table id="todo_field" class="detail">';
            echo '<tr><th colspan="2" class="title">Todo Fields</th></tr>';
            foreach ($todoFields as $field) {
                $todoTag = & $field->tags('@todo');
                echo '<tr>';
                echo '<td class="name"><a href="', $field->asPath(), '">', $field->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($todoTag)
                    echo strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        if ($todoMethods) {
            echo '<table id="todo_method" class="detail">';
            echo '<tr><th colspan="2" class="title">Todo Methods</th></tr>';
            foreach ($todoMethods as $method) {
                $todoTag = & $method->tags('@todo');
                echo "<tr>\n";
                echo '<td class="name"><a href="', $method->asPath(), '">', $method->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($todoTag)
                    echo strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        if ($todoGlobals) {
            echo '<table id="todo_global" class="detail">';
            echo '<tr><th colspan="2" class="title">Todo Globals</th></tr>';
            foreach ($todoGlobals as $global) {
                $todoTag = & $global->tags('@todo');
                echo "<tr>\n";
                echo '<td class="name"><a href="', $global->asPath(), '">', $global->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($todoTag)
                    echo strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        if ($todoFunctions) {
            echo '<table id="todo_function" class="detail">';
            echo '<tr><th colspan="2" class="title">Todo Functions</th></tr>';
            foreach ($todoFunctions as $function) {
                $todoTag = & $function->tags('@todo');
                echo '<tr>';
                echo '<td class="name"><a href="', $function->asPath(), '">', $function->qualifiedName(), '</a></td>';
                echo '<td class="description">';
                if ($todoTag)
                    echo strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('todo-list.html', 'Todo', TRUE);
    }
}
