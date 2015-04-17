<?php
/** This generates the list of interfaces and classes for a given package.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/packageWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 */

class packageWriter extends htmlWriter {

    /** Build the package summaries.
     * @param object &$doclet The reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $this->id = 'package';
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);

        foreach ($packages as $packageName => $package) {
            $this->id = $package->name;

            $output = [] ;
            $this->depth = $package->depth() + 1;

            $this->sections[0] = ['title' => 'Overview',   'url' => $index.'.html'];
            $this->sections[1] = ['title' => 'Namespace', 'selected' => TRUE];
            $this->sections[2] = ['title' => 'Class'];
            $this->sections[3] = ['title' => $package->name.'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $output['namespace'] = $package->name;
            $output['shortView'] =  __('Описания нет');
            if (!empty($package->desc)) {
                $output['shortView'] = $package->desc;
                $output['overview']  = str_replace(LF, '<br />', $package->overview);
            }

            $classes = &$package->ordinaryClasses();
            if ($classes) {
                foreach ($classes as $name => $class) {
                    $output['class'][$name]['path'] = str_repeat('../', $this->depth).$class->asPath();
                    $output['class'][$name]['name'] = $class->name;
                    $text = (isset($class->tags['@text'])) ? $class->tags['@text'] : __('Описания нет');
                    $output['class'][$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
            }

            $interfaces = &$package->interfaces();
            if ($interfaces) {
                ksort($interfaces);
                foreach ($interfaces as $name => $interface) {
                    $output['interface'][$name]['path'] = str_repeat('../', $this->depth).$interface->asPath();
                    $output['interface'][$name]['name'] = $interface->name;
                    $text = (isset($interface->tags['@text'])) ? $interface->tags['@text'] : __('Описания нет');
                    $output['interface'][$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
            }

            $traits = &$package->traits();
            if ($traits) {
                ksort($traits);
                foreach ($traits as $name => $trait) {
                    $output['trait'][$name]['path'] = str_repeat('../', $this->depth).$trait->asPath();
                    $output['trait'][$name]['name'] = $trait->name;
                    $text = (isset($trait->tags['@text'])) ? $trait->tags['@text'] : __('Описания нет');
                    $output['trait'][$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
            }

            $exceptions = &$package->exceptions();
            if ($exceptions) {
                ksort($exceptions);
                foreach ($exceptions as $name => $exception) {
                    $output['exception'][$name]['path'] = str_repeat('../', $this->depth).$exception->asPath();
                    $output['exception'][$name]['name'] = $exception->name;
                    $text = (isset($exception->tags['@text'])) ? $exception->tags['@text'] : __('Описания нет');
                    $output['exception'][$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
            }

            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                foreach ($functions as $name => $function) {
                    $output['function'][$name]['path'] = str_repeat('../', $this->depth).$function->asPath();
                    $output['function'][$name]['name'] = $function->name;
                    $text = (isset($function->tags['@text'])) ? $function->tags['@text'] : __('Описания нет');
                    $output['function'][$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
            }

            $globals = &$package->globals;
            if ($globals) {
                ksort($globals);
                foreach ($globals as $name => $global) {
                    $output['global'][$name]['path'] = str_repeat('../', $this->depth).$global->asPath();
                    $output['global'][$name]['name'] = $global->name;
                    $text = (isset($global->tags['@text'])) ? $global->tags['@text'] : __('Описания нет');
                    $output['global'][$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
            }

            $this->items = $this->packageItems($doclet->rootDoc->phpapi, $package, $this->depth);
            $this->tree($package, $package->asPath().DS.'package-tree', $package->name);

            $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'package-summary.tpl');
            ob_start();

            echo $tpl->parse($output);

            $this->output = ob_get_contents();
            ob_end_clean();
            $this->write($package->asPath().DS.'package-summary.html', $package->name);
        }
        $this->sections[0] = ['title' => 'Overview',   'url' => $index.'.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',  'selected' => TRUE];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $this->depth = 0;
        $this->tree(NULL, 'tree', 'Overview');
    }

    /** Builds tree of a given elements.
     * @param object $package The package to build tree from
     * @param string $dest    The file to represent a tree
     * @param string $name    The package to build tree for
     */
    private function tree($package, $dest, $name) {
        $this->id = 'tree';

        if ($package)
             $classes = &$package->ordinaryClasses();
        else $classes = &$this->doclet->rootDoc->classes();

        $output['tree'] = [];
        if ($classes) {
            $this->displayTree($classes, $output['tree']);

            if ($package) {
                $output['package'] = $name;
                $tpl = new template($this->doclet->rootDoc->phpapi->options['doclet'], 'package-tree.tpl');
            } else {
                $tpl = new template($this->doclet->rootDoc->phpapi->options['doclet'], 'tree.tpl');
            }
            ob_start();

            echo $tpl->parse($output);

            $this->output = ob_get_contents();
            ob_end_clean();
            $this->write($dest.'.html', $name);
        } else {
            $this->sections[3] = ['title' => 'Tree'];
        }
    }

    /** Build the package tree branch for the given element.
     * This function is recursive.
     * @param  array  $tree    Tree data
     * @param  array  &$output The reference the result array
     * @param  string parent   Parent element (Default = NULL)
     * @return array           Element of the tree by reference
     */
    private function displayTree($tree, &$output, $parent = NULL) {
        $outputList = TRUE;
        foreach ($tree as $i => $element) {
            $name = explode('.', $i);
            if ($element->superclass === $parent) {
                if ($outputList) $output[]['item'] = '<ul>';
                $output[]['item'] = '
                    <li>
                        <a href="'.str_repeat('../', $this->depth).$element->parent->asPath().DS.'package-summary.html">'.$element->parent->name.'</a> \
                        <a href="'.str_repeat('../', $this->depth).$element->asPath().'">'.$element->name.'</a>';
                $this->displayTree($tree, $output, $name[0]);
                $output[]['item'] = '</li>';
                $outputList = FALSE;
            }
        }
        if (!$outputList) $output[]['item'] = '</ul>';
    }
}
