<?php
/**
 * This generates the HTML API documentation for each individual interface and class.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/classWriter.php
 * @package   html
 */

class classWriter extends htmlWriter {

    /**
     * Builds the class definitons.
     *
     * @param doclet &$doclet Reference to documentation generator
     * @param string $index   Page name
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $key => $package) {
            $this->sections[0] = ['title' => 'Overview',   'url' => $index.'.html'];
            $this->sections[1] = ['title' => 'Namespace',  'url' => $package->path().DS.'package-summary.html'];
            $this->sections[2] = ['title' => 'Class', 'selected' => TRUE];
            $this->sections[3] = ['title' => $package->name.'\Tree', 'url' => $package->path().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $this->depth = $package->depth() + 1;

            $classes = &$package->classes;
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {
                    $this->id = $class->name;

                    $tpl = new template();
                    $tpl->set('package',  $class->package);
                    $tpl->set('location', $class->location());
                    if ($class->isInterface())
                         $tpl->set('qualified', 'Interface '.$class->name);
                    else $tpl->set('qualified', 'Class '.$class->name);

                    $result = $this->buildTree($class);
                    $tpl->set('tree', $result[0]);

                    if (count($class->interfaces) > 0) {
                        $output = [];
                        $i = 0;
                        foreach ($class->interfaces as $interface) {
                            $output[$i]['name'] = '<a href="'.str_repeat('../', $this->depth).$interface->path().'">';
                            if ($interface->package !==$class->package) {
                                $output[$i]['name'] .= $interface->package.'\\';
                            }
                            $output[$i]['name'] .= $interface->name.'</a> ';
                            $i++;
                        }
                        $tpl->set('implements', $output);
                    }

                    $traits = &$class->traits;
                    if (count($traits) > 0) {
                        $output = [];
                        $i = 0;
                        foreach ($traits as $trait) {
                            $output[$i]['name'] = '<a href="'.str_repeat('../', $this->depth).$trait->path().'">';
                            if ($trait->package !==$class->package) {
                                $output[$i]['name'] .= $trait->package.'\\';
                            }
                            $output[$i]['name'] .= $trait->name.'</a> ';
                            $i++;
                        }
                        $tpl->set('traits', $output);
                    }

                    $subclasses = $class->subclasses();
                    if ($subclasses) {
                        $output = [];
                        $i = 0;
                        foreach ($subclasses as $i => $subclass) {
                            $output[$i]['name'] = '<a href="'.str_repeat('../', $this->depth).$subclass->path().'">';
                            if ($subclass->package !==$class->package) {
                                $output[$i]['name'] .= $subclass->package.'\\';
                            }
                            $output[$i]['name'] .= $subclass->name.'</a> ';
                            $i++;
                        }
                        $tpl->set('subclasses', $output);
                    }

                    if     ($class->isInterface()) $tpl->set('is', 'interface');
                    elseif ($class->trait)         $tpl->set('is', 'trait');
                    else                           $tpl->set('is', 'class');

                    $tpl->set('ismodifiers', $class->modifiers());
                    $tpl->set('isname',      $class->name);

                    $text = (isset($class->tags['@text'])) ? $class->tags['@text'] : __('No description');
                    $tpl->set('textTag',    $this->processInlineTags($text));
                    $tpl->set('mainParams', $this->parameters($class->tags));

                    if ($class->constants) {
                        ksort($class->constants);
                        $tpl->set('constants', $this->showObject($class->constants));
                    }

                    $constructor = &$class->constructor();
                    $destructor  = &$class->destructor();
                    $methods     = &$class->methods(TRUE);
                    ksort($methods);

                    if ($class->fields) {
                        ksort($class->fields);
                        $tpl->set('fields', $this->showObject($class->fields));
                    }

                    if ($class->superclass) {
                        $superclass = &$doclet->rootDoc->classNamed($class->superclass);
                        if ($superclass) {
                            $i = 0;
                            $inherits = [];
                            $this->inherits($superclass, $package, 'fields',  $inherits,  $i);
                            $tpl->set('inheritFields',  $inherits);
                            $this->inherits($superclass, $package, 'methods', $inherits, $i);
                            $tpl->set('inheritMethods', $inherits);
                            $tpl->set('extends', ' extends <a href="'.str_repeat('../', $this->depth).$superclass->path().'">'.$superclass->name.'</a>');
                        } else {
                            $tpl->set('extends', ' extends '.$class->superclass.LF);
                        }
                    }

                    if ($constructor) {
                        $tpl->set('constructor', TRUE);
                        $tpl->set('c_location',  $constructor->location());
                        $tpl->set('c_modifiers', $constructor->modifiers());
                        $tpl->set('c_type',      $constructor->returnType());
                        $tpl->set('c_name',      $constructor->name);
                        $tpl->set('c_arguments', $constructor->arguments());
                        $text = (isset($constructor->tags['@text'])) ? $constructor->tags['@text'] : __('No description');
                        $tpl->set('c_shortDesc', strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>'));
                        $tpl->set('c_fullDesc',   $this->processInlineTags($text));
                        $tpl->set('c_parameters', $this->parameters($constructor->tags, $constructor));
                        $output = '';
                        if (!empty($constructor->includes)) {
                            foreach($constructor->includes as $key => $file) {
                                $file = substr(preg_replace("#[\'\"](.*?)[\'\"]#is", '<span class="red">\'\\1\'</span>', $file), 0, -1);
                                $output .= preg_replace("#^(.+?) #is", '<span class="bold">\\1</span> ', $file).'<br/>';
                            }
                            $tpl->set('c_includes', $output).LF;
                        }
                    }

                    if ($destructor) {
                        $tpl->set('destructor', TRUE);
                        $tpl->set('d_location',  $constructor->location());
                        $tpl->set('d_modifiers', $constructor->modifiers());
                        $tpl->set('d_type',      $constructor->returnType());
                        $tpl->set('d_name',      $constructor->name);
                        $tpl->set('d_arguments', $constructor->arguments());
                        $text = (isset($constructor->tags['@text'])) ? $destructor->tags['@text'] : __('No description');
                        $tpl->set('d_shortDesc', strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>'));
                        $tpl->set('d_fullDesc',   $this->processInlineTags($text));
                        $tpl->set('d_parameters', $this->parameters($destructor->tags, $destructor));
                    }
                    if ($methods) $tpl->set('methods', $this->showObject($methods));

                    if (method_exists('classItems', 'classItems')) $this->items = classItems::classItems($this->doclet, $package, $this->depth);
                    $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'class');
                    $this->write($package->path().DS.strtolower($class->name).'.html', 'API for class '.$class->name);
                }
            }
        }
    }

    /**
     * Builds the class hierarchy tree which is placed at the top of the page.
     *
     * @param  classDoc &$class Reference to the class to generate tree for
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
                $output .= '</li>
                        </ul>';
            }
            $output .= '</li>';
        } else $output .= '<li><a href="'.str_repeat('../', $this->depth).$class->path().'">'.$class->name.'</a>';

        return [$output, $depth];
    }

    /**
     * Displays the inherited fields or methods of an element.
     * This method calls itself recursively if the element has a parent class.
     *
     * @param  elementDoc &$element Reference to the class to generate tree for
     * @param  packageDoc &$package Reference to the current package
     * @param  string     $type     Field or method
     * @param  string     &$output  Reference to the output array
     * @param  integer    $i        Iterator
     * @return string               Output data about inherit fields or methods
     */
    private function inherits(&$element, &$package, $type, &$output, $i) {
        $items = ($type === 'fields') ? $element->$type : $element->$type();
        if ($items) {
            ksort($items);
            $num = count($items);
            $foo = 0;
            $output[$i]['fullNamespace'] = $element->fullNamespace();
            $output[$i]['name'] = [];
            foreach ($items as $k => $item) {
                $output[$i]['name'][$k]['path'] = str_repeat('../', $this->depth).$item->path();
                $output[$i]['name'][$k]['name'] = (++$foo < $num) ? $item->name.', ' : $item->name;
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
