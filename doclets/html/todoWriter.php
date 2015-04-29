<?php
/**
 * This generates the todo list.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      doclets/html/todoWriter.php
 * @package   html
 */

class todoWriter extends htmlWriter {

    /**
     * Builds the todo index.
     *
     * @param doclet &$doclet Reference to the documentation generator
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
        $output = $menu = [];
        $tpl = new template();

        $classes = &$doclet->rootDoc->classes();
        if ($classes) {
            foreach ($classes as $i => $class) {
                $todo = (isset($class->tags['@todo'])) ? $class->tags['@todo'] : NULL;
                if (!empty($todo)) {
                    $output['class'][$i]['path'] = $class->asPath();
                    $output['class'][$i]['name'] = $class->fullNamespace();
                    $output['class'][$i]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                    $menu[0]['class']   = TRUE;
                    $tpl->set('classes', $output['class']);
                }

                if ($class->fields) {
                    foreach ($class->fields as $k => $field) {
                        $todo = (isset($field->tags['@todo'])) ? $field->tags['@todo'] : NULL;
                        if (!empty($todo)) {
                            $output['field'][$k]['path'] = $field->asPath();
                            $output['field'][$k]['name'] = $field->fullNamespace();
                            $output['field'][$k]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                            $menu[0]['field']   = TRUE;
                            $tpl->set('fields', $output['field']);
                        }
                    }
                }

                $methods = &$class->methods();
                if ($methods) {
                    foreach ($methods as $k => $method) {
                        $todo = (isset($method->tags['@todo'])) ? $method->tags['@todo'] : NULL;
                        if (!empty($todo)) {
                            $output['method'][$k]['path'] = $method->asPath();
                            $output['method'][$k]['name'] = $method->fullNamespace();
                            $output['method'][$k]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                            $menu[0]['method']  = TRUE;
                            $tpl->set('methods', $output['method']);
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
                    $output['function'][$k]['path']  = $function->asPath();
                    $output['function'][$k]['name']  = $function->fullNamespace();
                    $output['function'][$k]['desc']  = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                    $menu[0]['function'] = TRUE;
                    $tpl->set('functions', $output['function']);
                }
            }
        }

        $globals = &$doclet->rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $todo = (isset($global->tags['@todo'])) ? $global->tags['@todo'] : NULL;
                if (!empty($todo)) {
                    $output['global'][$k]['path'] = $global->asPath();
                    $output['global'][$k]['name'] = $global->fullNamespace();
                    $output['global'][$k]['desc'] = strip_tags($this->processInlineTags($todo, TRUE), '<a><b><strong><u><em>');
                    $menu[0]['global']  = TRUE;
                    $tpl->set('globals', $output);
                }
            }
        }
        $tpl->set('menu', $menu);
        $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'todo');
        $this->write('todo.html', 'Todo');
    }
}
