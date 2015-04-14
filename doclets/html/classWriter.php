<?php
/** This generates the HTML API documentation for each individual interface and class.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/classWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 */

class classWriter extends htmlWriter {

    /** Build the class definitons.
     * @param object &$doclet The reference to documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $key => $package) {
            $this->sections[0] = ['title' => 'Overview',   'url' => $index.'.html'];
            $this->sections[1] = ['title' => 'Namespace',  'url' => $package->asPath().DS.'package-summary.html'];
            $this->sections[2] = ['title' => 'Class', 'selected' => TRUE];
            $this->sections[3] = ['title' => $package->name.'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $this->depth = $package->depth() + 1;

            $classes = &$package->classes;
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {
                    $this->id = $class->name;
                    $output   = [];
                    $output['package']  = $class->package;
                    $output['location'] = $class->location();
                    if ($class->isInterface())
                         $output['qualified'] = 'Interface '.$class->name;
                    else $output['qualified'] = 'Class '.$class->name;

                    $result = $this->buildTree($class);
                    $output['tree'] = $result[0];

                    if (count($class->interfaces) > 0) {
                        $i = 0;
                        foreach ($class->interfaces as $interface) {
                            $output['implements'][$i]['name'] = '<a href="'.str_repeat('../', $this->depth).$interface->asPath().'">';
                            if ($interface->package !==$class->package) {
                                $output['implements'][$i]['name'] .= $interface->package.'\\';
                            }
                            $output['implements'][$i]['name'] .= $interface->name.'</a> ';
                            $i++;
                        }
                    }

                    $traits = &$class->traits;
                    if (count($traits) > 0) {
                        $i = 0;
                        foreach ($traits as $trait) {
                            $output['trait'][$i]['name'] = '<a href="'.str_repeat('../', $this->depth).$trait->asPath().'">';
                            if ($trait->package !==$class->package) {
                                $output['trait'][$i]['name'] .= $trait->package.'\\';
                            }
                            $output['trait'][$i]['name'] .= $trait->name.'</a> ';
                            $i++;
                        }
                    }

                    $subclasses = $class->subclasses();
                    if ($subclasses) {
                        $i = 0;
                        foreach ($subclasses as $i => $subclass) {
                            $output['subclass'][$i]['name'] = '<a href="'.str_repeat('../', $this->depth).$subclass->asPath().'">';
                            if ($subclass->package !==$class->package) {
                                $output['subclass'][$i]['name'] .= $subclass->package.'\\';
                            }
                            $output['subclass'][$i]['name'] .= $subclass->name.'</a> ';
                            $i++;
                        }
                    }

                    if     ($class->isInterface()) $output['is'] = 'interface';
                    elseif ($class->trait)
                         $output['is'] = 'trait';
                    else $output['is'] = 'class';
                    $output['ismodifiers'] = $class->modifiers();
                    $output['isname']      = $class->name;

                    $text = (isset($class->tags['@text'])) ? $class->tags['@text'] : __('Описания нет');
                    $output['textag']    = $this->processInlineTags($text);
                    $output['main_tags'] = $this->processTags($class->tags);

                    if ($class->constants) {
                        ksort($class->constants);
                        $output['constant'] = $this->showObject($class->constants);
                    }

                    $constructor = &$class->constructor();
                    $destructor  = &$class->destructor();
                    $methods     = &$class->methods(TRUE);
                    ksort($methods);

                    if ($class->fields) {
                        ksort($class->fields);
                        $output['field'] = $this->showObject($class->fields);
                    }

                    if ($class->superclass) {
                        $superclass = &$doclet->rootDoc->classNamed($class->superclass);
                        if ($superclass) {
                            $i = 0;
                            $output['inheritFields']  = [];
                            $output['inheritMethods'] = [];
                            $this->inherits($superclass, $package, 'fields',  $output['inheritFields'],  $i);
                            $this->inherits($superclass, $package, 'methods', $output['inheritMethods'], $i);
                            $output['extends'] = ' extends <a href="'.str_repeat('../', $this->depth).$superclass->asPath().'">'.$superclass->name.'</a>';
                        } else {
                            $output['extends'] = ' extends '.$class->superclass.LF;
                        }
                    }

                    if ($constructor) {
                        $output['constructor'] = TRUE;
                        $output['location']    = $constructor->location();
                        $output['modifiers']   = $constructor->modifiers();
                        $output['type']        = $constructor->returnTypeAsString();
                        $output['name']        = $constructor->name;
                        $output['signature']   = $constructor->signature();
                        $text = (isset($constructor->tags['@text'])) ? $constructor->tags['@text'] : __('Описания нет');
                        $output['shortDesc']   = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                        $output['fullDesc']    = $this->processInlineTags($text);
                        $output['tags']        = $this->processTags($constructor->tags, $constructor);
                    }

                    if ($destructor) {
                        $output['destructor'] = TRUE;
                        $output['location']   = $destructor->location();
                        $output['modifiers']  = $destructor->modifiers();
                        $output['type']       = $destructor->returnTypeAsString();
                        $output['name']       = $destructor->name;
                        $output['signature']  = $destructor->signature();
                        $text = (isset($destructor->tags['@text'])) ? $destructor->tags['@text'] : __('Описания нет');
                        $output['shortDesc']  = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                        $output['fullDesc']   = $this->processInlineTags($text);
                        $output['tag']        = $this->processTags($destructor->tags, $destructor);
                    }
                    if ($methods) $output['method'] = $this->showObject($methods);

                    if (method_exists('classItems', 'classItems')) $this->items = classItems::classItems($this->doclet, $package, $this->depth);

                    $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'class.tpl');
                    ob_start();

                    echo $tpl->parse($output);

                    $this->output = ob_get_contents();
                    ob_end_clean();
                    $this->write($package->asPath().DS.strtolower($class->name).'.html', 'API for class '.$class->name);
                }
            }
        }
    }

    /** Builds the class hierarchy tree which is placed at the top of the page.
     * @param  classDoc &$class The reference the class to generate tree for
     * @param  integer  $depth  Depth of recursion (Default = NULL)
     * @return array            Output string and depth of recursion
     */
    private function buildTree(&$class, $depth = NULL) {
        if ($depth === NULL) {
            $start = TRUE;
            $depth = 0;
        } else {
            $start = FALSE;
        }
        $output = '';
        $undefinedClass = FALSE;
        if ($class->superclass) {
            $superclass = &$this->doclet->rootDoc->classNamed($class->superclass);
            if ($superclass) {
                $result  = $this->buildTree($superclass, $depth);
                $output .= $result[0];
                $depth   = ++$result[1];
            }
        }
        if ($depth > 0 && !$undefinedClass) $output .= '<ul>';

        if ($start) {
            $output .= '<li><strong>'.$class->name.'</strong></li></ul>';
            for($i = 1; $i < $depth;  $i++) {
                $output .= '</li>'
                    . '</ul>';
            }
            $output .= '</li>';
        } else $output .= '<li><a href="'.str_repeat('../', $this->depth).$class->asPath().'">'.$class->name.'</a>';

        return [$output, $depth];
    }

    /** Displays the inherited fields or methods of an element.
     * This method calls itself recursively if the element has a parent class.
     * @param  elementDoc &$element The reference the class to generate tree for
     * @param  packageDoc &$package The reference the current package
     * @param  string     $type     Field or method
     * @param  string     &$output  The reference the output array
     * @param  integer    $i        Iterator
     * @return string               Output data about inherit fields or methods
     */
    private function inherits(&$element, &$package, $type, &$output, $i) {
        $items = ($type === 'fields') ? $element->$type : $element->$type();
        if ($items) {
            ksort($items);
            $num = count($items);
            $foo = 0;
            $output[$i]['qualifiedName'] = $element->qualifiedName();
            $output[$i]['path']  = '';
            $output[$i]['name']  = '';
            $output[$i]['comma'] = '';
            foreach ($items as $item) {
                $output[$i]['path'] .= str_repeat('../', $this->depth).$item->asPath();
                $output[$i]['name'] .= $item->name;
                if (++$foo < $num) $output[$i]['name'] .= ', ';
            }
            if ($element->superclass) {
                $superclass = &$this->doclet->rootDoc->classNamed($element->superclass);
                if ($superclass) {
                    $i++;
                    $this->inherits($superclass, $package, $type, $output, $i);
                }
            }
        }
    }
}
