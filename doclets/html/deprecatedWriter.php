<?php
/** Generates the index of deprecated elements.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/deprecatedWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 */

class deprecatedWriter extends htmlWriter {

    /** Build the deprecated index.
     * @param object &$doclet The reference to the documentation generator
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
        $classes  = &$doclet->rootDoc->classes();
        $output   = [];

        if ($classes) {
            foreach ($classes as $i => $class) {
                $deprecatedTag = (isset($class->tags['@deprecated'])) ? $class->tags['@deprecated'] : NULL;
                if ($deprecatedTag) {
                    $output['class'][$i]['path'] = $class->asPath();
                    $output['class'][$i]['name'] = $class->qualifiedName();
                    $text = (isset($class->tags['@text'])) ? $class->tags['@text'] : __('Описания нет');
                    $output['class'][$i]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['class']  = TRUE;
                }

                if ($class->fields) {
                    foreach ($class->fields as $k => $field) {
                        $deprecatedTag = (isset($field->tags['@deprecated'])) ? $field->tags['@deprecated'] : NULL;
                        if ($deprecatedTag) {
                            $output['field'][$k]['path'] = $field->asPath();
                            $output['field'][$k]['name'] = $field->qualifiedName();
                            $text = (isset($field->tags['@text'])) ? $field->tags['@text'] : __('Описания нет');
                            $output['field'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['field']  = TRUE;
                        }
                    }
                }
                $classes = &$class->methods();
                if ($classes) {
                    foreach ($classes as $k => $method) {
                        $deprecatedTag = (isset($method->tags['@deprecated'])) ? $method->tags['@deprecated'] : NULL;
                        if ($deprecatedTag) {
                            $output['method'][$k]['path'] = $method->asPath();
                            $output['method'][$k]['name'] = $method->qualifiedName();
                            $text = (isset($method->tags['@text'])) ? $method->tags['@text'] : __('Описания нет');
                            $output['method'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['method']  = TRUE;
                        }
                    }
                }
            }
        }

        $functions = &$doclet->rootDoc->functions();
        if ($functions) {
            foreach ($functions as $k => $function) {
                $deprecatedTag = (isset($function->tags['@deprecated'])) ? $function->tags['@deprecated'] : NULL;
                if ($deprecatedTag) {
                    $output['function'][$k]['path'] = $function->asPath();
                    $output['function'][$k]['name'] = $function->qualifiedName();
                    $text = (isset($function->tags['@text'])) ? $function->tags['@text'] : __('Описания нет');
                    $output['function'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['function']  = TRUE;
                }
            }
        }

        $globals = &$doclet->rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $deprecatedTag = (isset($global->tags['@deprecated'])) ? $global->tags['@deprecated'] : NULL;
                if ($deprecatedTag) {
                    $output['global'][$k]['path'] = $global->asPath();
                    $output['global'][$k]['name'] = $global->qualifiedName();
                    $text = (isset($global->tags['@text'])) ? $global->tags['@text'] : __('Описания нет');
                    $output['global'][$k]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['global']  = TRUE;
                }
            }
        }

        $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'deprecated.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('deprecated.html', 'Deprecated');
    }
}
