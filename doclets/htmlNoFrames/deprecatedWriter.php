<?php
# phpapi: The PHP Documentation Creator

/** Generates the index of deprecated elements.
 *
 * @file      doclets/htmlNoFrames/deprecatedWriter.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class deprecatedWriter extends htmlWriter {

    /** Build the deprecated index.
     * @param Doclet doclet
     */
    public function deprecatedWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->_sections[0] = ['title' => 'Overview',        'url' => 'index.html'];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        $this->_sections[3] = ['title' => 'Tree',            'url' => 'tree.html'];
        $this->_sections[4] = ['title' => 'Deprecated', 'selected' => TRUE];
        $this->_sections[5] = ['title' => 'Todo',            'url' => 'todo.html'];
        $this->_sections[6] = ['title' => 'Index',           'url' => 'index-all.html'];

        $rootDoc =& $this->_doclet->rootDoc();
        $phpapi  =& $this->_doclet->phpapi();
        $classes =& $rootDoc->classes();
        $output  = [];

        if ($classes) {
            foreach ($classes as $i => $class) {
                $deprecatedTag =& $class->tags('@deprecated');
                if (!empty($deprecatedTag)) {
                    $output['classes'][$i]['path'] = $class->asPath();
                    $output['classes'][$i]['name'] = $class->qualifiedName();
                    $textTag =& $field->tags('@text');
                    $output['classes'][$i]['desc'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['classes'] = TRUE;
                }

                $fields =& $class->fields();
                if ($fields) {
                    foreach ($fields as $k => $field) {
                        $deprecatedTag =& $class->tags('@deprecated');
                        if (!empty($deprecatedTag)) {
                            $output['fields'][$k]['path'] = $field->asPath();
                            $output['fields'][$k]['name'] = $field->qualifiedName();
                            $textTag =& $field->tags('@text');
                            $output['fields'][$k]['desc'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['fields'] = TRUE;
                        }
                    }
                }
                $classes =& $class->methods();
                if ($classes) {
                    foreach ($classes as $k => $method) {
                        $deprecatedTag =& $method->tags('@deprecated');
                        if (!empty($deprecatedTag)) {
                            $output['methods'][$k]['path'] = $method->asPath();
                            $output['methods'][$k]['name'] = $method->qualifiedName();
                            $textTag =& $field->tags('@text');
                            $output['methods'][$k]['desc'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['methods'] = TRUE;
                        }
                    }
                }
            }
        }

        $globals =& $rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $deprecatedTag =& $global->tags('@deprecated');
                if (!empty($deprecatedTag)) {
                    $output['globals'][$k]['path'] = $global->asPath();
                    $output['globals'][$k]['name'] = $global->qualifiedName();
                    $textTag =& $field->tags('@text');
                    $output['globals'][$k]['desc'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['globals'] = TRUE;
                }
            }
        }

        $functions =& $rootDoc->functions();
        if ($functions) {
            foreach ($functions as $k => $function) {
                $deprecatedTag =& $function->tags('@deprecated');
                if (!empty($deprecatedTag)) {
                    $output['functions'][$k]['path'] = $function->asPath();
                    $output['functions'][$k]['name'] = $function->qualifiedName();
                    $textTag =& $field->tags('@text');
                    $output['functions'][$k]['desc'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['functions'] = TRUE;
                }
            }
        }

        $tpl = new template($phpapi->getOption('doclet'), 'deprecated.tpl');

        ob_start();

        echo $tpl->parse($output);

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('deprecated.html', 'Deprecated');
    }
}
