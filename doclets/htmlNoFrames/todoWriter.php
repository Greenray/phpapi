<?php
# phpapi: The PHP Documentation Creator

/** This generates the todo list.
 *
 * @file      doclets/htmlNoFrames/todoWriter.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class todoWriter extends htmlWriter {

    /** Build the todo index.
     * @param Doclet $doclet Link to documentation generator
     */
    public function todoWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->_sections[0] = ['title' => 'Overview',   'url' => 'index.html'];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        $this->_sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->_sections[5] = ['title' => 'Todo',  'selected' => TRUE];
        $this->_sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $rootDoc =& $this->_doclet->rootDoc();
        $phpapi  =& $this->_doclet->phpapi();
        $classes =& $rootDoc->classes();
        $output  = [];

        if ($classes) {
            foreach ($classes as $i => $class) {
                $todoTag =& $class->tags('@todo');
                if (!empty($todoTag)) {
                    $output['classes'][$i]['path'] = $class->asPath();
                    $output['classes'][$i]['name'] = $class->qualifiedName();
                    $output['classes'][$i]['desc'] = strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['classes'] = TRUE;
                }

                $fields =& $class->fields();
                if ($fields) {
                    foreach ($fields as $k => $field) {
                        $todoTag =& $field->tags('@todo');
                        if (!empty($todoTag)) {
                            $output['fields'][$k]['path'] = $field->asPath();
                            $output['fields'][$k]['name'] = $field->qualifiedName();
                            $output['fields'][$k]['desc'] = strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['fields'] = TRUE;
                        }
                    }
                }

                $methods =& $class->methods();
                if ($methods) {
                    foreach ($methods as $k => $method) {
                        $todoTag =& $method->tags('@todo');
                        if (!empty($todoTag)) {
                            $output['methods'][$k]['path'] = $method->asPath();
                            $output['methods'][$k]['name'] = $method->qualifiedName();
                            $output['methods'][$k]['desc'] = strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['methods'] = TRUE;
                        }
                    }
                }
            }
        }

        $globals =& $rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $todoTag =& $global->tags('@todo');
                if (!empty($todoTag)) {
                    $output['globals'][$k]['path'] = $global->asPath();
                    $output['globals'][$k]['name'] = $global->qualifiedName();
                    $output['globals'][$k]['desc'] = strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['globals'] = TRUE;
                }
            }
        }

        $functions =& $rootDoc->functions();
        if ($functions) {
            foreach ($functions as $k => $function) {
                $todoTag =& $function->tags('@todo');
                if (!empty($todoTag)) {
                    $output['functions'][$k]['path'] = $function->asPath();
                    $output['functions'][$k]['name'] = $function->qualifiedName();
                    $output['functions'][$k]['desc'] = strip_tags($this->_processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['functions'] = TRUE;
                }
            }
        }

        $tpl = new template($phpapi->getOption('doclet'), 'todo.tpl');

        ob_start();

        echo $tpl->parse($output);

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('todo.html', 'Todo');
    }
}
