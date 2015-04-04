<?php
/** Generates the index of deprecated elements.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlNoFrames/deprecatedWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class deprecatedWriter extends htmlWriter {

    /** Build the deprecated index.
     *
     * @param Doclet doclet
     */
    public function deprecatedWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->sections[0] = ['title' => 'Overview',        'url' => 'index.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',            'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'selected' => TRUE];
        $this->sections[5] = ['title' => 'Todo',            'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index',           'url' => 'index-all.html'];

        $rootDoc = &$this->doclet->rootDoc();
        $phpapi  = &$this->doclet->phpapi();
        $classes = &$rootDoc->classes();
        $output  = [];

        if ($classes) {
            foreach ($classes as $i => $class) {
                $deprecatedTag = &$class->tags('@deprecated');
                if (!empty($deprecatedTag)) {
                    $output['class'][$i]['path'] = $class->asPath();
                    $output['class'][$i]['name'] = $class->qualifiedName();
                    $textTag = &$field->tags('@text');
                    $output['class'][$i]['desc'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['class']  = TRUE;
                }

                $fields = &$class->fields();
                if ($fields) {
                    foreach ($fields as $k => $field) {
                        $deprecatedTag = &$field->tags('@deprecated');
                        if (!empty($deprecatedTag)) {
                            $output['field'][$k]['path'] = $field->asPath();
                            $output['field'][$k]['name'] = $field->qualifiedName();
                            $textTag = &$field->tags('@text');
                            $output['field'][$k]['desc'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['field']  = TRUE;
                        }
                    }
                }
                $classes = &$class->methods();
                if ($classes) {
                    foreach ($classes as $k => $method) {
                        $deprecatedTag = &$method->tags('@deprecated');
                        if (!empty($deprecatedTag)) {
                            $output['method'][$k]['path'] = $method->asPath();
                            $output['method'][$k]['name'] = $method->qualifiedName();
                            $textTag = &$field->tags('@text');
                            $output['method'][$k]['desc'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['menu'][0]['method']  = TRUE;
                        }
                    }
                }
            }
        }

        $functions = &$rootDoc->functions();
        if ($functions) {
            foreach ($functions as $k => $function) {
                $deprecatedTag = &$function->tags('@deprecated');
                if (!empty($deprecatedTag)) {
                    $output['function'][$k]['path'] = $function->asPath();
                    $output['function'][$k]['name'] = $function->qualifiedName();
                    $textTag = &$field->tags('@text');
                    $output['function'][$k]['desc'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['function']  = TRUE;
                }
            }
        }

        $globals = &$rootDoc->globals();
        if ($globals) {
            foreach ($globals as $k => $global) {
                $deprecatedTag = &$global->tags('@deprecated');
                if (!empty($deprecatedTag)) {
                    $output['global'][$k]['path'] = $global->asPath();
                    $output['global'][$k]['name'] = $global->qualifiedName();
                    $textTag = &$field->tags('@text');
                    $output['global'][$k]['desc'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    $output['menu'][0]['global']  = TRUE;
                }
            }
        }

        $tpl = new template($phpapi->options['doclet'], 'deprecated.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('deprecated.html', 'Deprecated');
    }
}
