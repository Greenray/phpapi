<?php
# phpapi: The PHP Documentation Creator

/** This generates the list of interfaces and classes for a given package.
 *
 * @file      doclets/standard/packageWriter.php
 * @version   1.0
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

        $this->_id = 'tree';
        $rootDoc   =& $this->_doclet->rootDoc();
        $phpapi    =& $this->_doclet->phpapi();

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

            ob_start();

            echo '<hr>';
            echo '<h1>Class Hierarchy</h1>';
            $this->displayTree($tree, $output['tree']);
//            echo $tpl->parse($output);
            echo '<hr>';

            $this->_output = ob_get_contents();
            ob_end_clean();

            $this->_write('tree.html', 'Overview', TRUE);
        }

        $this->_id = 'package';
        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {

            $this->_depth = $package->depth() + 1;

            $this->_sections[0] = ['title' => 'Overview',       'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace', 'selected' => TRUE];
            $this->_sections[2] = ['title' => 'Class'];
            if ($displayTree) $this->_sections[3] = ['title' => $package->name().'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->_sections[4] = ['title' => 'Deprecated',     'url' => 'deprecated.html'];
            $this->_sections[5] = ['title' => 'Todo',           'url' => 'todo.html'];
            $this->_sections[6] = ['title' => 'Index',          'url' => 'index-all.html'];

            ob_start();

            echo '<hr>';
            echo '<h1>Namespace '.$package->name().'</h1>';
            $textTag =& $package->tags('@text');
            if ($textTag) {
                echo '<div class="comment">', $this->_processInlineTags($textTag, TRUE), '</div>';
                echo '<dl><dt>See:</dt><dd><b><a href="#overview_description">Description</a></b></dd></dl>';
            }
            $classes =& $package->ordinaryClasses();
            if ($classes) {
                ksort($classes);
                echo '<table class="title">';
                echo '<tr><th colspan="2" class="title">Class Summary</th></tr>';
                foreach ($classes as $name => $class) {
                    $textTag =& $classes[$name]->tags('@text');
                    echo '<tr><td class="name"><a href="'.str_repeat('../', $this->_depth).$classes[$name]->asPath().'">'.$classes[$name]->name().'</a></td>';
                    echo '<td class="description">';
                    if ($textTag) {
                        echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                    echo '</td></tr>';
                }
                echo '</table>';
            }
            $interfaces =& $package->interfaces();
            if ($interfaces) {
                ksort($interfaces);
                echo '<table class="title">';
                echo '<tr><th colspan="2" class="title">Interface Summary</th></tr>';
                foreach ($interfaces as $name => $interface) {
                    $textTag =& $interfaces[$name]->tags('@text');
                    echo '<tr><td class="name"><a href="', str_repeat('../', $this->_depth), $interfaces[$name]->asPath(), '">', $interfaces[$name]->name(), '</a></td>';
                    echo '<td class="description">';
                    if ($textTag) {
                        echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                    echo '</td></tr>';
                }
                echo '</table>';
            }
            $traits =& $package->traits();
            if ($traits) {
                ksort($traits);
                echo '<table class="title">';
                echo '<tr><th colspan="2" class="title">Trait Summary</th></tr>';
                foreach ($traits as $name => $trait) {
                    $textTag =& $traits[$name]->tags('@text');
                    echo '<tr><td class="name"><a href="'.str_repeat('../', $this->_depth).$traits[$name]->asPath().'">'.$traits[$name]->name().'</a></td>';
                    echo '<td class="description">';
                    if ($textTag) {
                        echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                    echo '</td></tr>';
                }
                echo '</table>';
            }
            $exceptions =& $package->exceptions();
            if ($exceptions) {
                ksort($exceptions);
                echo '<table class="title">'.LF;
                echo '<tr><th colspan="2" class="title">Exception Summary</th></tr>';
                foreach ($exceptions as $name => $exception) {
                    $textTag =& $exceptions[$name]->tags('@text');
                    echo '<tr><td class="name"><a href="'.str_repeat('../', $this->_depth).$exceptions[$name]->asPath().'">', $exceptions[$name]->name().'</a></td>';
                    echo '<td class="description">';
                    if ($textTag) {
                        echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                    echo '</td></tr>';
                }
                echo '</table>';
            }
            $functions =& $package->functions();
            if ($functions) {
                ksort($functions);
                echo '<table class="title">';
                echo '<tr><th colspan="2" class="title">Function Summary</th></tr>';
                foreach ($functions as $name => $function) {
                    $textTag =& $functions[$name]->tags('@text');
                    echo '<tr><td class="name"><a href="package-functions.html#'.$functions[$name]->name().'">'.$functions[$name]->name().'</a></td>';
                    echo '<td class="description">';
                    if ($textTag) {
                        echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                    echo '</td></tr>';
                }
                echo '</table>';
            }

            $globals =& $package->globals();
            if ($globals) {
                ksort($globals);
                echo '<table class="title">'.LF;
                echo '<tr><th colspan="2" class="title">Global Summary</th></tr>';
                foreach ($globals as $name => $global) {
                    $textTag =& $globals[$name]->tags('@text');
                    echo '<tr><td class="name"><a href="package-globals.html#'.$globals[$name]->name().'">'.$globals[$name]->name().'</a></td>';
                    echo '<td class="description">';
                    if ($textTag) {
                        echo strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                    echo '</td></tr>';
                }
                echo '</table>';
            }

            $textTag =& $package->tags('@text');
            if ($textTag) {
                echo '<h1>Namespace '.$package->name().' Description</h1>';
                echo '<div class="comment" id="overview_description">'.$this->_processInlineTags($textTag), '</div>';
            }

            echo '<hr>';

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
                $tpl = new template($phpapi->getOption('doclet'), 'tree');

                ob_start();

                echo '<hr>';
                echo '<h1>Class Hierarchy for Package '.$package->name().'</h1>';
                $this->displayTree($tree, $output['tree']);
//                echo $tpl->parse($output);
                echo '<hr>';

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
    public function displayTree($tree, &$output, $parent = NULL) {
        $outputList = TRUE;
        foreach ($tree as $name => $element) {
            if ($element->superclass() == $parent) {
                if ($outputList) echo '<ul>';
                echo '<li><a href="', str_repeat('../', $this->_depth), $element->asPath(), '">', $element->qualifiedName(), '</a>';
                $this->displayTree($tree, $output, $name);
                echo '</li>';
                $outputList = FALSE;
            }
        }
        if (!$outputList) echo '</ul>';
    }
}
