<?php
/**
 * Generates the index of deprecated elements.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/deprecatedWriter.php
 * @package   html
 */

class deprecatedWriter extends htmlWriter {

    /**
     * Builds the deprecated index.
     *
     * @param doclet &$doclet Reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $this->sections[0] = ['title' => 'Overview',        'url' => $index.'.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',            'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'selected' => TRUE];
        $this->sections[5] = ['title' => 'Todo',            'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index',           'url' => 'index-all.html'];

        $this->id = 'deprecated';
        $output   = $menu = [];
        $tpl = new template();

        $classes  = &$doclet->rootDoc->classes();
        $tpl = new template();
        if ($classes) {
            foreach ($classes as $i => $class) {
                $deprecatedTag = (isset($class->tags['@deprecated'])) ? $class->tags['@deprecated'] : NULL;
                if ($deprecatedTag) {
                    $output['class'][$i]['path'] = $class->path();
                    $output['class'][$i]['name'] = $class->fullNamespace();
                    $text = (isset($class->tags['@text'])) ? $class->tags['@text'] : __('No description');
                    $output['class'][$i]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                    $menu[0]['class'] = TRUE;
                    $tpl->set('classes', $output['class']);
                }

                if ($class->fields) {
                    foreach ($class->fields as $k => $field) {
                        $deprecatedTag = (isset($field->tags['@deprecated'])) ? $field->tags['@deprecated'] : NULL;
                        if ($deprecatedTag) {
                            $output['field'][$k]['path'] = $field->path();
                            $output['field'][$k]['name'] = $field->fullNamespace();
                            $text = (isset($field->tags['@text'])) ? $field->tags['@text'] : __('No description');
                            $output['field'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                            $menu[0]['field'] = TRUE;
                            $tpl->set('fields', $output['field']);
                        }
                    }
                }

                $methods = &$class->methods();
                if ($methods) {
                    foreach ($methods as $k => $method) {
                        $deprecatedTag = (isset($method->tags['@deprecated'])) ? $method->tags['@deprecated'] : NULL;
                        if ($deprecatedTag) {
                            $output['method'][$k]['path'] = $method->path();
                            $output['method'][$k]['name'] = $method->fullNamespace();
                            $text = (isset($method->tags['@text'])) ? $method->tags['@text'] : __('No description');
                            $output['method'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                            $menu[0]['method'] = TRUE;
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
                $deprecatedTag = (isset($function->tags['@deprecated'])) ? $function->tags['@deprecated'] : NULL;
                if ($deprecatedTag) {
                    $output['function'][$k]['path'] = $function->path();
                    $output['function'][$k]['name'] = $function->fullNamespace();
                    $text = (isset($function->tags['@text'])) ? $function->tags['@text'] : __('No description');
                    $output['function'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                    $menu[0]['function'] = TRUE;
                    $tpl->set('functions', $output['function']);
                }
            }
        }

        $globals = &$doclet->rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $deprecatedTag = (isset($global->tags['@deprecated'])) ? $global->tags['@deprecated'] : NULL;
                if ($deprecatedTag) {
                    $output['global'][$k]['path'] = $global->path();
                    $output['global'][$k]['name'] = $global->fullNamespace();
                    $text = (isset($global->tags['@text'])) ? $global->tags['@text'] : __('No description');
                    $output['global'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                    $menu[0]['global'] = TRUE;
                    $tpl->set('globals', $output['global']);
                }
            }
        }
        $tpl->set('menu', $menu);
        $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'deprecated');
        $this->write('deprecated.html', 'Deprecated');
    }
}
