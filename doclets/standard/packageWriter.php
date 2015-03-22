<?php
# phpapi: The PHP Documentation Creator

/** This generates the list of interfaces and classes for a given package.
 *
 * @file      doclets/standard/packageWriter.php
 * @version   2.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class packageWriter extends htmlWriter {

    /** Build the package summaries.
     * @param Doclet doclet
     */
    public function packageWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->_id   = 'tree';
        $rootDoc     =& $this->_doclet->rootDoc();
        $phpapi      =& $this->_doclet->phpapi();
        $displayTree = $phpapi->getOption('tree');

        if ($displayTree) {
            $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace'];
            $this->_sections[2] = ['title' => 'Class'];
            $this->_sections[3] = ['title' => 'Tree',  'selected' => TRUE];
            $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
            $this->_sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
            $this->_sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $tree = [];
            $classes =& $rootDoc->classes();
            if ($classes) {
                foreach ($classes as $class) {
                    $this->buildTree($tree, $class);
                }
            }

            $output['tree'] = [];
            $tpl = new template($phpapi->getOption('doclet'), 'tree');
            $this->displayTree($tree, $output['tree'], 0);
            ob_start();

            echo $tpl->parse($output);

            $this->_output = ob_get_contents();
            ob_end_clean();
            $this->_write('tree.html', 'Overview', TRUE);
        }

        $this->_id = 'package';
        $packages =& $rootDoc->packages();
        ksort($packages);
        $output = [] ;
        foreach ($packages as $packageName => $package) {
            $this->_depth = $package->depth() + 1;

            $this->_sections[0] = ['title' => 'Overview',       'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace', 'selected' => TRUE];
            $this->_sections[2] = ['title' => 'Class'];
            if ($displayTree) $this->_sections[3] = ['title' => $package->name().'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->_sections[4] = ['title' => 'Deprecated',     'url' => 'deprecated.html'];
            $this->_sections[5] = ['title' => 'Todo',           'url' => 'todo.html'];
            $this->_sections[6] = ['title' => 'Index',          'url' => 'index-all.html'];

            $output['name'] = $package->name();
            $textTag =& $package->tags('@text');
            if ($textTag) {
                $output['shortOverview'] = $this->_processInlineTags($textTag, TRUE);
            }
            $classes =& $package->ordinaryClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {
                    $output['classes'][$name]['path'] = str_repeat('../', $this->_depth).$classes[$name]->asPath();
                    $output['classes'][$name]['name'] = $classes[$name]->name();
                    $textTag =& $classes[$name]->tags('@text');
                    if ($textTag) {
                        $output['classes'][$name]['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }
            $interfaces =& $package->interfaces();
            if ($interfaces) {
                ksort($interfaces);
                foreach ($interfaces as $name => $interface) {
                    $output['interfaces'][$name]['path'] = str_repeat('../', $this->_depth).$interfaces[$name]->asPath();
                    $output['interfaces'][$name]['name'] = $interfaces[$name]->name();
                    $textTag =& $interfaces[$name]->tags('@text');
                    if ($textTag) {
                        $output['interfaces'][$name]['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }
            $traits =& $package->traits();
            if ($traits) {
                ksort($traits);
                foreach ($traits as $name => $trait) {
                    $output['traits'][$name]['path'] = str_repeat('../', $this->_depth).$traits[$name]->asPath();
                    $output['traits'][$name]['name'] = $traits[$name]->name();
                    $textTag =& $traits[$name]->tags('@text');
                    if ($textTag) {
                        $output['traits'][$name]['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }
            $exceptions =& $package->exceptions();
            if ($exceptions) {
                ksort($exceptions);
                foreach ($exceptions as $name => $exception) {
                    $output['exceptions'][$name]['path'] = str_repeat('../', $this->_depth).$exceptions[$name]->asPath();
                    $output['exceptions'][$name]['name'] = $exceptions[$name]->name();
                    $textTag =& $exceptions[$name]->tags('@text');
                    if ($textTag) {
                        $output['exceptions'][$name]['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }
            $functions =& $package->functions();
            if ($functions) {
                ksort($functions);
                foreach ($functions as $name => $function) {
                    $output['functions'][$name]['path'] = str_repeat('../', $this->_depth).$functions[$name]->asPath();
                    $output['functions'][$name]['name'] = $functions[$name]->name();
                    $textTag =& $functions[$name]->tags('@text');
                    if ($textTag) {
                        $output['functions'][$name]['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $globals =& $package->globals();
            if ($globals) {
                ksort($globals);
                foreach ($globals as $name => $global) {
                    $output['globals'][$name]['path'] = str_repeat('../', $this->_depth).$globals[$name]->asPath();
                    $output['globals'][$name]['name'] = $globals[$name]->name();
                    $textTag =& $globals[$name]->tags('@text');
                    if ($textTag) {
                        $output['globals'][$name]['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $textTag =& $package->tags('@text');
            if ($textTag) {
                $output['verview'] = $this->_processInlineTags($textTag);
            }
            $tpl = new template($phpapi->getOption('doclet'), 'package-summary');
            ob_start();

            echo $tpl->parse($output);

            $this->_output = ob_get_contents();
            ob_end_clean();
            $this->_write($package->asPath().DS.'package-summary.html', $package->name(), TRUE);

            if ($displayTree) {
                $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
                $this->_sections[1] = ['title' => 'Namespace',  'url' => $package->asPath().DS.'package-summary.html', 'relative' => TRUE];
                $this->_sections[2] = ['title' => 'Class'];
                $this->_sections[3] = ['title' => $package->name().'\Tree', 'url' => $package->asPath().DS.'package-tree.html', 'selected' => TRUE, 'relative' => TRUE];
                $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
                $this->_sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
                $this->_sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

                $this->_id = 'tree';

                $tree = [];
                $classes =& $package->ordinaryClasses();
                if ($classes) {
                    ksort($classes);
                    foreach ($classes as $class) $this->buildTree($tree, $class);
                }

                $output['tree'] = [];
                $output['name'] = $package->name();
                $this->displayTree($tree, $output['tree'], 0);
                $tpl = new template($phpapi->getOption('doclet'), 'tree');
                ob_start();

                echo $tpl->parse($output);

                $this->_output = ob_get_contents();
                ob_end_clean();
                $this->_write($package->asPath().DS.'package-tree.html', $package->name(), TRUE);
            }
        }
    }

    /** Builds the class tree branch for the given element.
     * This function is recursive.
     * @param classDoc[] $tree    Link to class tree
     * @param classDoc   $element Link to element
     */
    public function buildTree(&$tree, &$element) {
        $tree[$element->name()] = $element;
        if ($element->superclass()) {
            $rootDoc =& $this->_doclet->rootDoc();
            $superclass =& $rootDoc->classNamed($element->superclass());
            if ($superclass) $this->buildTree($tree, $superclass);
        }
    }

    /** Build the class tree branch for the given element.
     * This function is recursive.
     * @param classDoc[] $tree   Tree data
     * @param string     $parent Parent element (Default = NULL)
     */
    public function displayTree($tree, &$output, $i, $parent = NULL) {
        $outputList = TRUE;
        foreach ($tree as $name => $element) {
            if ($element->superclass() == $parent) {
                if ($outputList) $output[]['item'] = '<ul>';
                $output[]['item'] = '<li><a href="'.str_repeat('../', $this->_depth).$element->asPath().'">'.$element->qualifiedName().'</a>';
                $this->displayTree($tree, $output, $i, $name);
                $output[]['item'] = '</li>';
                $outputList = FALSE;
            }
        }
        if (!$outputList) $output[]['item'] = '</ul>';
    }
}
