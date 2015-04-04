<?php
/** This generates the list of interfaces and classes for a given package.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlNoFrames/packageWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class packageWriter extends htmlWriter {

    /** Build the package summaries.
     *
     * @param Doclet doclet Documentation generator
     */
    public function packageWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $rootDoc  = &$this->doclet->rootDoc();
        $phpapi   = &$this->doclet->phpapi();
        $packages = &$rootDoc->packages;
        ksort($packages);

        foreach ($packages as $packageName => $package) {
            $output = [] ;
            $this->depth = $package->depth() + 1;

            $this->sections[0] = ['title' => 'Overview',               'url' => 'index.html'];
            $this->sections[1] = ['title' => 'Namespace',         'selected' => TRUE];
            $this->sections[2] = ['title' => 'Class'];
            $this->sections[3] = ['title' => $package->name().'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated',             'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',                   'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',                  'url' => 'index-all.html'];

            $output['namespace'] = $package->name();
            $textTag = &$package->tags('@text');
            if ($textTag) {
                $output['shortOverview'] = $this->processInlineTags($textTag, TRUE);
            }

            $classes = &$package->ordinaryClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {
                    $output['class'][$name]['path'] = str_repeat('../', $this->depth).$class->asPath();
                    $output['class'][$name]['name'] = $class->name();
                    $textTag = &$class->tags('@text');
                    $output['class'][$name]['description'] = __('Нет описания');
                    if ($textTag) {
                        $output['class'][$name]['description'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $interfaces = &$package->interfaces();
            if ($interfaces) {
                ksort($interfaces);
                foreach ($interfaces as $name => $interface) {
                    $output['interface'][$name]['path'] = str_repeat('../', $this->depth).$interface->asPath();
                    $output['interface'][$name]['name'] = $interface->name();
                    $textTag = &$interface->tags('@text');
                    $output['interface'][$name]['description'] = __('Нет описания');
                    if ($textTag) {
                        $output['interface'][$name]['description'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $traits = &$package->traits();
            if ($traits) {
                ksort($traits);
                foreach ($traits as $name => $trait) {
                    $output['trait'][$name]['path'] = str_repeat('../', $this->depth).$trait->asPath();
                    $output['trait'][$name]['name'] = $trait->name();
                    $textTag = &$trait->tags('@text');
                    $output['trait'][$name]['description'] = __('Нет описания');
                    if ($textTag) {
                        $output['trait'][$name]['description'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $exceptions = &$package->exceptions();
            if ($exceptions) {
                ksort($exceptions);
                foreach ($exceptions as $name => $exception) {
                    $output['exception'][$name]['path'] = str_repeat('../', $this->depth).$exception->asPath();
                    $output['exception'][$name]['name'] = $exception->name();
                    $textTag = &$exception->tags('@text');
                    $output['exeption'][$name]['description'] = __('Нет описания');
                    if ($textTag) {
                        $output['exception'][$name]['description'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $functions = &$package->functions();
            if ($functions) {
                ksort($functions);
                foreach ($functions as $name => $function) {
                    $output['function'][$name]['path'] = str_repeat('../', $this->depth).$function->asPath();
                    $output['function'][$name]['name'] = $function->name();
                    $textTag = &$function->tags('@text');
                    $output['function'][$name]['description'] = __('Нет описания');
                    if ($textTag) {
                        $output['function'][$name]['description'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $globals = &$package->globals();
            if ($globals) {
                ksort($globals);
                foreach ($globals as $name => $global) {
                    $output['global'][$name]['path'] = str_repeat('../', $this->depth).$global->asPath();
                    $output['global'][$name]['name'] = $global->name();
                    $textTag = &$global->tags('@text');
                    $output['global'][$name]['description'] = __('Нет описания');
                    if ($textTag) {
                        $output['global'][$name]['description'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                    }
                }
            }

            $textTag = &$package->tags('@text');
            if ($textTag) {
                $output['overview'] = $this->processInlineTags($textTag);
            }
            $this->items = $this->packageItems($phpapi, $package);
            $tpl = new template($phpapi->options['doclet'], 'package-summary.tpl');
            ob_start();

            echo $tpl->parse($output);

            $this->output = ob_get_contents();
            ob_end_clean();
            $this->write($package->asPath().DS.'package-summary.html', $package->name());

            $this->sections[0] = ['title' => 'Overview',   'url' => 'index.html'];
            $this->sections[1] = ['title' => 'Namespace',  'url' => $package->asPath().DS.'package-summary.html', 'relative' => TRUE];
            $this->sections[2] = ['title' => 'Class'];
            $this->sections[3] = ['title' => $package->name().'\Tree', 'url' => $package->asPath().DS.'package-tree.html', 'selected' => TRUE, 'relative' => TRUE];
            $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $this->items = $this->packageItems($phpapi, $package);
            $this->tree($package, $package->asPath().DS.'package-tree.html', $package->name());
        }
        $this->sections[0] = ['title' => 'Overview',   'url' => 'index.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',  'selected' => TRUE];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $this->depth = 0;
        $this->tree(NULL, 'tree.html', 'Overview');
    }

    private function tree($package, $dest, $name) {
        $rootDoc   = &$this->doclet->rootDoc();
        $phpapi    = &$this->doclet->phpapi();

        $tree = [];
        if ($package)
             $classes = &$package->ordinaryClasses();
        else $classes = &$rootDoc->classes();

        if ($classes) {
            ksort($classes);
            foreach ($classes as $class) {
                $this->buildTree($tree, $class);
            }
        }

        $output['tree'] = [];
        $this->displayTree($tree, $output['tree'], 0);
        if ($package) {
            $output['package'] = $name;
            $tpl = new template($phpapi->options['doclet'], 'package-tree.tpl');
        } else {
            $tpl = new template($phpapi->options['doclet'], 'tree.tpl');
        }
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write($dest, $name);
    }

    /** Builds the class tree branch for the given element.
     * This function is recursive.
     *
     * @param classDoc[] $tree    Link to class tree
     * @param classDoc   $element Link to element
     */
    private function buildTree(&$tree, &$element) {
        $tree[$element->name()] = $element;
        if ($element->superclass()) {
            $rootDoc = &$this->doclet->rootDoc();
            $superclass = &$rootDoc->classNamed($element->superclass());
            if ($superclass) $this->buildTree($tree, $superclass);
        }
    }

    /** Build the class tree branch for the given element.
     * This function is recursive.
     *
     * @param classDoc[] $tree   Tree data
     * @param string     $parent Parent element (Default = NULL)
     */
    private function displayTree($tree, &$output, $i, $parent = NULL) {
        $outputList = TRUE;
        foreach ($tree as $name => $element) {
            if ($element->superclass() == $parent) {
                if ($outputList) $output[]['item'] = '<ul>';
                $output[]['item'] = '<li><a href="'.str_repeat('../', $this->depth).$element->asPath().'">'.$element->qualifiedName().'</a>';
                $this->displayTree($tree, $output, $i, $name);
                $output[]['item'] = '</li>';
                $outputList = FALSE;
            }
        }
        if (!$outputList) $output[]['item'] = '</ul>';
    }
}
