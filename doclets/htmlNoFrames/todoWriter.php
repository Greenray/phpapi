<?php
/** This generates the todo list.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlNoFrames/todoWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class todoWriter extends htmlWriter {

    /** Build the todo index.
     *
     * @param Doclet $doclet Link to documentation generator
     */
    public function todoWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->sections[0] = ['title' => 'Overview',   'url' => 'index.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',  'selected' => TRUE];
        $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $rootDoc = &$this->doclet->rootDoc();
        $phpapi  = &$this->doclet->phpapi();
        $classes = &$rootDoc->classes();
        $output  = [];

        if ($classes) {
            foreach ($classes as $i => $class) {
                $todoTag = &$class->tags('@todo');
                if (!empty($todoTag)) {
                    $output['classs'][$i]['path'] = $class->asPath();
                    $output['classs'][$i]['name'] = $class->qualifiedName();
                    $output['classs'][$i]['desc'] = strip_tags($this->processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['class']   = TRUE;
                }

                $fields = &$class->fields();
                if ($fields) {
                    foreach ($fields as $k => $field) {
                        $todoTag = &$field->tags('@todo');
                        if (!empty($todoTag)) {
                            $output['field'][$k]['path'] = $field->asPath();
                            $output['field'][$k]['name'] = $field->qualifiedName();
                            $output['field'][$k]['desc'] = strip_tags($this->processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['field']  = TRUE;
                        }
                    }
                }

                $methods = &$class->methods();
                if ($methods) {
                    foreach ($methods as $k => $method) {
                        $todoTag = &$method->tags('@todo');
                        if (!empty($todoTag)) {
                            $output['method'][$k]['path'] = $method->asPath();
                            $output['method'][$k]['name'] = $method->qualifiedName();
                            $output['method'][$k]['desc'] = strip_tags($this->processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['method']  = TRUE;
                        }
                    }
                }
            }
        }

        $functions = &$rootDoc->functions();
        if ($functions) {
            foreach ($functions as $k => $function) {
                $todoTag = &$function->tags('@todo');
                if (!empty($todoTag)) {
                    $output['function'][$k]['path'] = $function->asPath();
                    $output['function'][$k]['name'] = $function->qualifiedName();
                    $output['function'][$k]['desc'] = strip_tags($this->processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['function']  = TRUE;
                }
            }
        }

        $globals = &$rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $todoTag = &$global->tags('@todo');
                if (!empty($todoTag)) {
                    $output['global'][$k]['path'] = $global->asPath();
                    $output['global'][$k]['name'] = $global->qualifiedName();
                    $output['global'][$k]['desc'] = strip_tags($this->processInlineTags($todoTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['global']  = TRUE;
                }
            }
        }

        $tpl = new template($phpapi->options['doclet'], 'todo.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('todo.html', 'Todo');
    }
}
