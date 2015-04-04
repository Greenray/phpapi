<?php
/** This generates the HTML API documentation for each individual interface and class.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlFrames/classWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlFrames
 */

class classWriter extends htmlWriter {

    /** Build the class definitons.
     *
     * @param Doclet doclet Link to documentation generator
     */
    public function classWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $rootDoc   = &$this->doclet->rootDoc();
        $phpapi    = &$this->doclet->phpapi();
        $packages  = &$rootDoc->packages;
        ksort($packages);
        foreach ($packages as $key => $package) {
            $this->sections[0] = ['title' => 'Overview',               'url' => 'overview-summary.html'];
            $this->sections[1] = ['title' => 'Namespace',              'url' => $package->asPath().DS.'package-summary.html'];
            $this->sections[2] = ['title' => 'Class',             'selected' => TRUE];
            $this->sections[3] = ['title' => $package->name().'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->sections[4] = ['title' => 'Deprecated',             'url' => 'deprecated.html'];
            $this->sections[5] = ['title' => 'Todo',                   'url' => 'todo.html'];
            $this->sections[6] = ['title' => 'Index',                  'url' => 'index-all.html'];

            $this->depth = $package->depth() + 1;

            $classes = &$package->allClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {
                    $this->id = $class->name();
                    $output = [];
                    $output['package']  = $class->packageName();
                    $output['location'] = $class->location();
                    if ($class->isInterface())
                         $output['qualified'] = 'Interface '.$class->name();
                    else $output['qualified'] = 'Class '.$class->name();

                    $result = $this->buildTree($rootDoc, $classes[$name]);
                    $output['tree'] = $result[0];

                    $implements = &$class->interfaces();
                    if (count($implements) > 0) {
                        $output['implements'] = '';
                        foreach ($implements as $interface) {
                            $output['implements'] .= '<a href="'.str_repeat('../', $this->depth).$interface->asPath().'">';
                            if ($interface->packageName() != $class->packageName()) {
                                $output['implements'] .= $interface->packageName().'\\';
                            }
                            $output['implements'] .= $interface->name().'</a> ';
                        }
                    }

                    $traits = &$class->traits();
                    if (count($traits) > 0) {
                        $output['trait'] = '';
                        foreach ($traits as $trait) {
                            $output['trait'] .= '<a href="'.str_repeat('../', $this->depth).$trait->asPath().'">';
                            if ($trait->packageName() != $class->packageName()) {
                                $output['trait'] .= $trait->packageName().'\\';
                            }
                            $output['trait'] .= $trait->name().'</a> ';
                        }
                    }

                    $subclasses = $class->subclasses();
                    if ($subclasses) {
                        $output['subclass'] = '';
                        foreach ($subclasses as $subclass) {
                            $output['subclass'] .= '<a href="'.str_repeat('../', $this->depth).$subclass->asPath().'">';
                            if ($subclass->packageName() != $class->packageName()) {
                                $output['subclass'] .= $subclass->packageName().'\\';
                            }
                            $output['subclass'] .= $subclass->name().'</a> ';
                        }
                    }

                    if     ($class->isInterface()) $output['is'] = 'interface';
                    elseif ($class->isTrait())     $output['is'] = 'trait';
                    else                           $output['is'] = 'class';
                    $output['ismodifiers'] = $class->modifiers();
                    $output['isname']      = $class->name();

                    $textTag = &$class->tags('@text');
                    if ($textTag)
                         $output['textag'] = $this->processInlineTags($textTag);
                    else $output['textag'] = _('Описания нет');

                    $output['main_tags'] = $this->processTags($class->tags());

                    $constants = &$class->constants();
                    ksort($constants);
                    $fields = &$class->fields();
                    ksort($fields);
                    $constructor = &$class->constructor();
                    $destructor  = &$class->destructor();
                    $methods     = &$class->methods(TRUE);
                    ksort($methods);

                    if ($constants) $output['constant'] = $this->showObject($constants);
                    if ($fields)    $output['field']    = $this->showObject($fields);

                    if ($class->superclass()) {
                        $superclass = &$rootDoc->classNamed($class->superclass());
                        if ($superclass) {
                            $i = 0;
                            $output['inheritFields']  = [];
                            $output['inheritMethods'] = [];
                            $this->inherits($superclass, $rootDoc, $package, 'fields',  $output['inheritFields'],  $i);
                            $this->inherits($superclass, $rootDoc, $package, 'methods', $output['inheritMethods'], $i);
                            $output['extends'] = ' extends <a href="'.str_repeat('../', $this->depth).$superclass->asPath().'">'.$superclass->name().'</a>';
                        } else {
                            $output['extends'] = ' extends '.$class->superclass().LF;
                        }
                    }

                    if ($constructor) {
                        $output['constructor'] = TRUE;
                        $output['location']    = $constructor->location();
                        $output['modifiers']   = $constructor->modifiers();
                        $output['type']        = $constructor->returnTypeAsString();
                        $output['name']        = $constructor->name();
                        $output['signature']   = $constructor->signature();
                        $textTag = &$constructor->tags('@text');
                        if ($textTag) {
                            $output['shortDesc'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['fullDesc']  = $this->processInlineTags($textTag);
                        } else {
                            $output['shortDesc'] = __('Описания нет');
                            $output['fullDesc']  = __('Описания нет');
                        }
                        $output['tags'] = $this->processTags($constructor->tags());
                    }

                    if ($destructor) {
                        $output['destructor'] = TRUE;
                        $output['location']   = $destructor->location();
                        $output['modifiers']  = $destructor->modifiers();
                        $output['type']       = $destructor->returnTypeAsString();
                        $output['name']       = $destructor->name();
                        $output['signature']  = $destructor->signature();
                        $textTag = &$destructor->tags('@text');
                        if ($textTag) {
                            $output['shortDesc'] = strip_tags($this->processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['fullDesc']  = $this->processInlineTags($textTag);
                        } else {
                            $output['shortDesc'] = __('Описания нет');
                            $output['fullDesc']  = __('Описания нет');
                        }
                        $output['tag'] = $this->processTags($destructor->tags());
                    }
                    if ($methods) $output['method'] = $this->showObject($methods);

                    $tpl = new template($phpapi->options['doclet'], 'class.tpl');
                    ob_start();

                    echo $tpl->parse($output);

                    $this->output = ob_get_contents();
                    ob_end_clean();
                    $this->write($package->asPath().DS.strtolower($class->name()).'.html', 'API for class '.$class->name());
                }
            }
        }
    }

    /** Build the class hierarchy tree which is placed at the top of the page.
     *
     * @param  rootDoc  $rootDoc Link to root doc
     * @param  classDoc $class   Link to class to generate tree for
     * @param  integer  $depth   Depth of recursion (Default = NULL)
     * @return array             Output string and depth of recursion
     */
    private function buildTree(&$rootDoc, &$class, $depth = NULL) {
        if ($depth === NULL) {
            $start = TRUE;
            $depth = 0;
        } else {
            $start = FALSE;
        }
        $output = '';
        $undefinedClass = FALSE;
        if ($class->superclass()) {
            $superclass = &$rootDoc->classNamed($class->superclass());
            if ($superclass) {
                $result  = $this->buildTree($rootDoc, $superclass, $depth);
                $output .= $result[0];
                $depth   = ++$result[1];
            }
        }
        if ($depth > 0 && !$undefinedClass) $output .= '<ul>';

        if ($start) {
            $output .= '<li><strong>'.$class->name().'</strong></li></ul>';
            for($i = 1; $i < $depth;  $i++) {
                $output .= '</li>'
                    . '</ul>';
            }
            $output .= '</li>';
        } else $output .= '<li><a href="'.str_repeat('../', $this->depth).$class->asPath().'">'.$class->name().'</a>';

        return [$output, $depth];
    }

    /** Display the inherited fields or methods of an element.
     * This method calls itself recursively if the element has a parent class.
     *
     * @param  elementDoc $element Link to class to generate tree for
     * @param  rootDoc    $rootDoc Link to root document
     * @param  packageDoc $package Link to current package
     * @param  string     $type    Field or method
     * @param  string     $output  Link to output array
     * @param  integer    $i       Iterator
     * @return string              Output data about inherit fields or methods
     */
    private function inherits(&$element, &$rootDoc, &$package, $type, &$output, $i) {
        $items = &$element->$type();
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
                $output[$i]['name'] .= $item->name();
                if (++$foo < $num) $output[$i]['name'] .= ', ';
            }
            if ($element->superclass()) {
                $superclass = &$rootDoc->classNamed($element->superclass());
                if ($superclass) {
                    $i++;
                    $this->inherits($superclass, $rootDoc, $package, $type, $output, $i);
                }
            }
        }
    }
}
