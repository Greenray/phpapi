<?php
/** This generates the todo list.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/todoWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 */

class todoWriter extends htmlWriter {

    /** Build the todo index.
     * @param object &$doclet The reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $this->sections[0] = ['title' => 'Overview',   'url' => $index.'.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',  'selected' => TRUE];
        $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $this->id = 'todo';
        $classes  = &$doclet->rootDoc->classes();
        $output   = [];

        if ($classes) {
            foreach ($classes as $i => $class) {
                $todo = (isset($class->tags['@todo'])) ? $class->tags['@todo'] : NULL;
                if (!empty($todo)) {
                    $output['class'][$i]['path'] = $class->asPath();
                    $output['class'][$i]['name'] = $class->qualifiedName();
                    $output['class'][$i]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['class']  = TRUE;
                }

                if ($class->fields) {
                    foreach ($class->fields as $k => $field) {
                        $todo = (isset($field->tags['@todo'])) ? $field->tags['@todo'] : NULL;
                        if (!empty($todo)) {
                            $output['field'][$k]['path'] = $field->asPath();
                            $output['field'][$k]['name'] = $field->qualifiedName();
                            $output['field'][$k]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['field']  = TRUE;
                        }
                    }
                }

                $methods = &$class->methods();
                if ($methods) {
                    foreach ($methods as $k => $method) {
                        $todo = (isset($method->tags['@todo'])) ? $method->tags['@todo'] : NULL;
                        if (!empty($todo)) {
                            $output['method'][$k]['path'] = $method->asPath();
                            $output['method'][$k]['name'] = $method->qualifiedName();
                            $output['method'][$k]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['method']  = TRUE;
                        }
                    }
                }
            }
        } else {
            $this->sections[3] = ['title' => 'Tree'];
        }

        $functions = &$doclet->rootDoc->functions();
        if ($functions) {
            foreach ($functions as $k => $function) {
                $todo = (isset($function->tags['@todo'])) ? $function->tags['@todo'] : NULL;
                if (!empty($todo)) {
                    $output['function'][$k]['path'] = $function->asPath();
                    $output['function'][$k]['name'] = $function->qualifiedName();
                    $output['function'][$k]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['function']  = TRUE;
                }
            }
        }

        $globals = &$doclet->rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $todo = (isset($global->tags['@todo'])) ? $global->tags['@todo'] : NULL;
                if (!empty($todo)) {
                    $output['global'][$k]['path'] = $global->asPath();
                    $output['global'][$k]['name'] = $global->qualifiedName();
                    $output['global'][$k]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['global']  = TRUE;
                }
            }
        }

        $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'todo.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('todo.html', 'Todo');
    }
}
