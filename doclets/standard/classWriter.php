<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each individual interface and class.
 *
 * @file      doclets/standard/classWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class classWriter extends htmlWriter {

    /** Build the class definitons.
     * @param Doclet doclet
     */
    public function classWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->_id = 'definition';
        $rootDoc   =& $this->_doclet->rootDoc();
        $phpapi    =& $this->_doclet->phpapi();
        $packages  =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',  'url' => $package->asPath().DS.'package-summary.html'];
            $this->_sections[2] = ['title' => 'Class', 'selected' => TRUE];
            if ($phpapi->getOption('tree'))
                $this->_sections[3] = ['title' => 'Tree',   'url' => $package->asPath().DS.'package-tree.html'];
            $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated-list.html'];
            $this->_sections[5] = ['title' => 'Todo',       'url' => 'todo-list.html'];
            $this->_sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            $classes =& $package->allClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {

                    ob_start();

                    $output = [];
                    $output['qualified'] = $class->qualifiedName();

                    $this->_sourceLocation($class);
                    if ($class->isInterface())
                         $output['qualifiedName'] = 'Interface '.$class->name();
                    else $output['qualifiedName'] = 'Class '.$class->name();
                    $result = $this->_buildTree($rootDoc, $classes[$name]);
                    $output['tree'] = $result[0];

                    $implements =& $class->interfaces();
                    if (count($implements) > 0) {
                        $output['implements'] = '';
                        foreach ($implements as $interface) {
                            $output['implements'] .= '<a href="'.str_repeat('../', $this->_depth).$interface->asPath().'">';
                            if ($interface->packageName() != $class->packageName()) {
                                $output['implements'] .= $interface->packageName().'\\';
                            }
                            $output['implements'] .= $interface->name().'</a> ';
                        }
                    }

                    $traits =& $class->traits();
                    if (count($traits) > 0) {
                        $output['traits'] = '';
                        foreach ($traits as $trait) {
                            $output['traits'] .= '<a href="'.str_repeat('../', $this->_depth).$trait->asPath().'">';
                            if ($trait->packageName() != $class->packageName()) {
                                $output['traits'] .= $trait->packageName().'\\';
                            }
                            $output['traits'] .= $trait->name().'</a> ';
                        }
                    }

                    $subclasses = $class->subclasses();
                    if ($subclasses) {
                        $output['subclasses'] = '';
                        foreach ($subclasses as $subclass) {
                            $output['subclasses'] .= '<a href="'.str_repeat('../', $this->_depth).$subclass->asPath().'">';
                            if ($subclass->packageName() != $class->packageName()) {
                                $output['subclasses'] .= $subclass->packageName().'\\';
                            }
                            $output['subclasses'] .= $subclass->name().'</a> ';
                        }
                    }

                    if ($class->isInterface()) $output['is'] = 'interface';
                    elseif ($class->isTrait()) $output['is'] = 'trait';
                    else                       $output['is'] = 'class';
                    $output['ismodifiers'] = $class->modifiers();
                    $output['isname'] = $class->name();

                    if ($class->superclass()) {
                        $superclass =& $rootDoc->classNamed($class->superclass());
                        if ($superclass)
                             $output['extends'] = ' extends <a href="'.str_repeat('../', $this->_depth).$superclass->asPath().'">'.$superclass->name().'</a>';
                        else $output['extends'] = ' extends '.$class->superclass().LF;
                    }

                    $textTag =& $class->tags('@text');
                    if ($textTag)
                         $output['textag'] = $this->_processInlineTags($textTag);
                    else $output['textag'] = _('Описания нет');

                    $output['tags'] = $this->_processTags($class->tags());

                    $constants =& $class->constants();
                    ksort($constants);
                    $fields =& $class->fields();
                    ksort($fields);
                    $constructor =& $class->constructor();
                    $methods =& $class->methods(TRUE);
                    ksort($methods);

                    if ($constants) $output['constant'] = $this->showObject($constants, FALSE);
                    if ($fields)    $output['field']    = $this->showObject($fields, FALSE);

                    if ($class->superclass()) {
                        $superclass =& $rootDoc->classNamed($class->superclass());
                        if ($superclass) {
                            $i = 0;
                            $output['inheritFields'] = [];
                            $this->inheritFields($superclass, $rootDoc, $package, $output['inheritFields'], $i);
                        }
                    }

                    if ($constructor) {
                        $output['constructor'] = TRUE;
                        $output['modifiers']   = $constructor->modifiers(FALSE);
                        $output['type']        = $constructor->returnTypeAsString();
                        $output['name']        = $constructor->name();
                        $output['signature']   = $constructor->signature();
                        $textTag =& $constructor->tags('@text');
                        if ($textTag)
                             $output['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                        else $output['description'] = __('Описания нет');
                    }

                    if ($methods) $output['method'] = $this->showObject($methods, FALSE);

                    if ($class->superclass()) {
                        $superclass =& $rootDoc->classNamed($class->superclass());
                        if ($superclass) {
                            $i = 0;
                            $output['inheritMethods'] = [];
                            $this->inheritMethods($superclass, $rootDoc, $package, $output['inheritMethods'], $i);
                        }
                    }

                    if ($constants) $output['constants'] = $this->showObject($constants);
                    if ($fields)    $output['fields']    = $this->showObject($fields);

                    if ($constructor) {
                        $output['constructors'] = TRUE;
                        $output['location']     = $this->_sourceLocation($constructor);
                        $output['modifiers']    = $constructor->modifiers();
                        $output['type']         = $constructor->returnTypeAsString();
                        $output['name']         = $constructor->name();
                        $output['signature']    = $constructor->signature();
                        $textTag =& $constructor->tags('@text');
                        if ($textTag)
                             $output['description'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                        else $output['description'] = __('Описания нет');
                        $output['tags'] = $this->_processTags($constructor->tags());
                    }

                    if ($methods) $output['methods'] = $this->showObject($methods);

                    $tpl = new template($phpapi->getOption('doclet'), 'classes');
                    echo $tpl->parse($output);

                    $this->_output = ob_get_contents();
                    ob_end_clean();

                    $this->_write($package->asPath().'/'.strtolower($class->name()).'.html', $class->name(), TRUE);
                }
            }
        }
    }

    /** Build the class hierarchy tree which is placed at the top of the page.
     * @param rootDoc rootDoc The root doc
     * @param classDoc class Class to generate tree for
     * @param integer depth Depth of recursion
     * @return mixed[]
     */
    public function _buildTree(&$rootDoc, &$class, $depth = NULL) {
        if ($depth === NULL) {
            $start = TRUE;
            $depth = 0;
        } else {
            $start = FALSE;
        }

        $output = '';
        $undefinedClass = FALSE;
        if ($class->superclass()) {
            $superclass =& $rootDoc->classNamed($class->superclass());
            if ($superclass) {
                $result  = $this->_buildTree($rootDoc, $superclass, $depth);
                $output .= $result[0];
                $depth = ++$result[1];
            } else {
                $output .= $class->superclass().'<br>';
                $output .= str_repeat('   ', $depth).' └─';
                $depth++;
                $undefinedClass = TRUE;
            }
        }

        if ($depth > 0 && !$undefinedClass) $output .= str_repeat('   ', $depth).' └─';

        if ($start)
             $output .= '<strong>'.$class->name().'</strong><br>';
        else $output .= '<a href="'.str_repeat('../', $this->_depth).$class->asPath().'">'.$class->name().'</a><br>';

        return [$output, $depth];
    }

    /** Display the inherited fields of an element.
     * This method calls itself recursively if the element has a parent class.
     * @param ProgramElementDoc element
     * @param rootDoc rootDoc
     * @param packageDoc package
     */
    public function inheritFields(&$element, &$rootDoc, &$package, &$output, $i) {
        $fields =& $element->fields();
        if ($fields) {
            ksort($fields);
            $class = $element->qualifiedName();
            $pos   = strrpos($class, '\\');
            if ($pos != FALSE) $class = substr($class, $pos + 1);
            $num = count($fields);
            $foo = 0;
            $output[$class]['qualifiedName'] = $element->qualifiedName();
            $output[$class]['field'] = '';
            foreach ($fields as $field) {
                $output[$class]['field'] .= '<a href="'.str_repeat('../', $this->_depth).$field->asPath().'">'.$field->name().'</a>';
                if (++$foo < $num) $output[$class]['field'] .= ', ';
            }
            if ($element->superclass()) {
                $superclass =& $rootDoc->classNamed($element->superclass());
                if ($superclass) {
                    $i++;
                    $this->inheritFields($superclass, $rootDoc, $package, $output, $i);
                }
            }
        }
    }

    /** Display the inherited methods of an element.
     * This method calls itself recursively if the element has a parent class.
     * @param ProgramElementDoc element
     * @param rootDoc rootDoc
     * @param packageDoc package
     */
    public function inheritMethods(&$element, &$rootDoc, &$package, &$output, $i) {
        $methods =& $element->methods();
        if ($methods) {
            ksort($methods);
            $num = count($methods);
            $foo = 0;
            $output[$i]['qualifiedName'] = $element->qualifiedName();
            $output[$i]['method'] = '';
            foreach ($methods as $method) {
                $output[$i]['method'] .= '<a href="'.str_repeat('../', $this->_depth).$method->asPath().'">'.$method->name().'</a>';
                if (++$foo < $num) $output[$i]['method'] .= ', ';
            }
            if ($element->superclass()) {
                $superclass =& $rootDoc->classNamed($element->superclass());
                if ($superclass) {
                    $i++;
                    $this->inheritMethods($superclass, $rootDoc, $package, $output, $i);
                }
            }
        }
    }
}
