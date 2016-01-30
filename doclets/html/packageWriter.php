<?php
/**
 * This generates the list of interfaces and classes for a given package.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      doclets/html/packageWriter.php
 * @package   html
 */

class packageWriter extends htmlWriter {

    /**
     * Builds the package summaries.
     *
     * @param doclet &$doclet Reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $packages = &$doclet->rootDoc->packages;
        ksort($packages);

        foreach ($packages as $packageName => $package) {
            $this->id    = $package->name;
            $this->depth = $package->depth() + 1;

            $this->sections[0] = ['title' => 'Overview',   'url' => $index.'.html'];
            $this->sections[1] = ['title' => 'Namespace', 'selected' => TRUE];
            $this->sections[2] = ['title' => 'Class'];
            $this->sections[3] = ['title' => $package->name.'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $tpl = new template();
            $tpl->set('namespace', $package->name);
            $tpl->set('shortView', __('No description'));
            if (!empty($package->desc)) {
                $tpl->set('shortView', $package->desc);
                $tpl->set('overView', str_replace(LF, '<br />', $package->overview));
            }

            $classes = &$package->ordinaryClasses();
            if ($classes) {
                $output = [];
                foreach ($classes as $name => $class) {
                    $output[$name]['path'] = str_repeat('../', $this->depth).$class->asPath();
                    $output[$name]['name'] = $class->name;
                    $text = (isset($class->tags['@text'])) ? $class->tags['@text'] : __('No description');
                    $output[$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
                $tpl->set('classes', $output);
            }

            $interfaces = &$package->interfaces();
            if ($interfaces) {
                ksort($interfaces);
                $output = [];
                foreach ($interfaces as $name => $interface) {
                    $output[$name]['path'] = str_repeat('../', $this->depth).$interface->asPath();
                    $output[$name]['name'] = $interface->name;
                    $text = (isset($interface->tags['@text'])) ? $interface->tags['@text'] : __('No description');
                    $output[$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
                $tpl->set('interfaces', $output);
            }

            $traits = &$package->traits();
            if ($traits) {
                ksort($traits);
                $output = [];
                foreach ($traits as $name => $trait) {
                    $output[$name]['path'] = str_repeat('../', $this->depth).$trait->asPath();
                    $output[$name]['name'] = $trait->name;
                    $text = (isset($trait->tags['@text'])) ? $trait->tags['@text'] : __('No description');
                    $output[$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
                $tpl->set('traits', $output);
            }

            $exceptions = &$package->exceptions();
            if ($exceptions) {
                ksort($exceptions);
                $output = [];
                foreach ($exceptions as $name => $exception) {
                    $output[$name]['path'] = str_repeat('../', $this->depth).$exception->asPath();
                    $output[$name]['name'] = $exception->name;
                    $text = (isset($exception->tags['@text'])) ? $exception->tags['@text'] : __('No description');
                    $output[$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
                $tpl->set('exceptions', $output);
            }

            $functions = &$package->functions;
            if ($functions) {
                ksort($functions);
                $output = [];
                foreach ($functions as $name => $function) {
                    $output[$name]['path'] = str_repeat('../', $this->depth).$function->asPath();
                    $output[$name]['name'] = $function->name;
                    $text = (isset($function->tags['@text'])) ? $function->tags['@text'] : __('No description');
                    $output[$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
                $tpl->set('functions', $output);
            }

            $globals = &$package->globals;
            if ($globals) {
                ksort($globals);
                $output = [];
                foreach ($globals as $name => $global) {
                    $output[$name]['path'] = str_repeat('../', $this->depth).$global->asPath();
                    $output[$name]['name'] = $global->name;
                    $text = (isset($global->tags['@text'])) ? $global->tags['@text'] : __('No description');
                    $output[$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                }
                $tpl->set('globals', $output);
            }

            $this->tree($package, $package->asPath().DS.'package-tree', $package->name);
            if ($doclet->rootDoc->phpapi->options['doclet'] === 'plain') {
                $this->items = $this->packageItems($doclet->rootDoc->phpapi, $package);
            }

            $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'package-summary');
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

    /**
     * Builds tree of a given elements.
     *
     * @param object $package Package to build tree from
     * @param string $dest    File to represent a tree
     * @param string $name    Package to build tree for
     */
    private function tree($package, $dest, $name) {
        $this->id = 'tree';

        if ($package)
             $classes = &$package->ordinaryClasses();
        else $classes = &$this->doclet->rootDoc->classes();

        if ($classes) {
            $tree = [];
            $this->displayTree($classes, $tree);
            $tpl  = new template();
            $tpl->set('tree', $tree);
            $file = 'tree';
            if ($package) {
                $tpl->set('package', $name);
                $file = 'package-tree';
            }
            $this->output = $tpl->parse($this->doclet->rootDoc->phpapi, $file);
            $this->write($dest.'.html', $name);

        } else {
            $this->sections[3] = ['title' => 'Tree'];
        }
    }

    /**
     * Build the package tree branch for the given element.
     * This function is recursive.
     *
     * @param  array  $elements Elements of tree
     * @param  array  &$output  Reference the result array
     * @param  string parent    Parent element (Default = NULL)
     * @return array            Element of the tree by reference
     */
    private function displayTree($elements, &$tree, $parent = NULL) {
        $list = TRUE;
        foreach ($elements as $i => $element) {
            $name = explode('.', $i);
            if ($element->superclass === $parent) {
                if ($list) $tree[]['item'] = '<ul>';
                $tree[]['item'] = '<li>
                    <a href="'.str_repeat('../', $this->depth).$element->parent->asPath().DS.'package-summary.html">'.$element->parent->name.'</a> \
                    <a href="'.str_repeat('../', $this->depth).$element->asPath().'">'.$element->name.'</a>';
                $this->displayTree($elements, $tree, $name[0]);
                $tree[]['item'] = '</li>';
                $list = FALSE;
            }
        }
        if (!$list) $tree[]['item'] = '</ul>';
    }
}
