<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each individual interface and class.
 *
 * @file      doclets/htmlFrames/classWriter.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlFrames
 */

class classWriter extends htmlWriter {

    /** Build the class definitons.
     * @param Doclet doclet Link to documentation generator
     */
    public function classWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->_id = 'definition';
        $rootDoc   =& $this->_doclet->rootDoc();
        $phpapi    =& $this->_doclet->phpapi();
        $packages  =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $key => $package) {
            $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',  'url' => $package->asPath().DS.'package-summary.html'];
            $this->_sections[2] = ['title' => 'Class', 'selected' => TRUE];
            if ($phpapi->getOption('tree')) $this->_sections[3] = ['title' => $package->name().'\Tree', 'url' => $package->asPath().DS.'package-tree.html'];
            $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
            $this->_sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
            $this->_sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            $classes =& $package->allClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {
                    $output = [];
                    $output['package']  = $class->packageName();
                    $output['location'] = $class->location();
                    if ($class->isInterface())
                         $output['qualified'] = 'Interface '.$class->name();
                    else $output['qualified'] = 'Class '.$class->name();

                    $result = $this->buildTree($rootDoc, $classes[$name]);
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

                    if     ($class->isInterface()) $output['is'] = 'interface';
                    elseif ($class->isTrait())     $output['is'] = 'trait';
                    else                           $output['is'] = 'class';
                    $output['ismodifiers'] = $class->modifiers();
                    $output['isname']      = $class->name();

                    $textTag =& $class->tags('@text');
                    if ($textTag)
                         $output['textag'] = $this->_processInlineTags($textTag);
                    else $output['textag'] = _('Описания нет');

                    $output['main_tags'] = $this->_processTags($class->tags());

                    $constants =& $class->constants();
                    ksort($constants);
                    $fields =& $class->fields();
                    ksort($fields);
                    $constructor =& $class->constructor();
                    $destructor  =& $class->destructor();
                    $methods     =& $class->methods(TRUE);
                    ksort($methods);

                    if ($constants) $output['constant'] = $this->showObject($constants);
                    if ($fields)    $output['field']    = $this->showObject($fields);

                    if ($class->superclass()) {
                        $superclass =& $rootDoc->classNamed($class->superclass());
                        if ($superclass) {
                            $i = 0;
                            $output['inheritFields']  = [];
                            $output['inheritMethods'] = [];
                            $this->inherits($superclass, $rootDoc, $package, 'fields',  $output['inheritFields'],  $i);
                            $this->inherits($superclass, $rootDoc, $package, 'methods', $output['inheritMethods'], $i);
                            $output['extends'] = ' extends <a href="'.str_repeat('../', $this->_depth).$superclass->asPath().'">'.$superclass->name().'</a>';
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
                        $textTag =& $constructor->tags('@text');
                        if ($textTag) {
                            $output['shortDesc'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['fullDesc']  = $this->_processInlineTags($textTag);
                        } else {
                            $output['shortDesc'] = __('Описания нет');
                            $output['fullDesc']  = __('Описания нет');
                        }
                        $output['tags'] = $this->_processTags($constructor->tags());
                    }
                    if ($destructor) {
                        $output['destructor'] = TRUE;
                        $output['location']   = $destructor->location();
                        $output['modifiers']  = $destructor->modifiers();
                        $output['type']       = $destructor->returnTypeAsString();
                        $output['name']       = $destructor->name();
                        $output['signature']  = $destructor->signature();
                        $textTag =& $destructor->tags('@text');
                        if ($textTag) {
                            $output['shortDesc'] = strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>');
                            $output['fullDesc']  = $this->_processInlineTags($textTag);
                        } else {
                            $output['shortDesc'] = __('Описания нет');
                            $output['fullDesc']  = __('Описания нет');
                        }
                        $output['tags'] = $this->_processTags($destructor->tags());
                    }
                    if ($methods) $output['method'] = $this->showObject($methods);

                    $tpl = new template($phpapi->getOption('doclet'), 'class.tpl');
                    ob_start();

                    echo $tpl->parse($output);

                    $this->_output = ob_get_contents();
                    ob_end_clean();
                    $this->_write($package->asPath().DS.strtolower($class->name()).'.html', 'API for class '.$class->name());
                }
            }
        }
    }

    /** Build the class hierarchy tree which is placed at the top of the page.
     * @param  rootDoc  $rootDoc Link to root doc
     * @param  classDoc $class   Link to class to generate tree for
     * @param  integer  $depth   Depth of recursion (Default = NULL)
     * @return array             Output string and depth of recursion
     */
    public function buildTree(&$rootDoc, &$class, $depth = NULL) {
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
        } else $output .= '<li><a href="'.str_repeat('../', $this->_depth).$class->asPath().'">'.$class->name().'</a>';

        return [$output, $depth];
    }

    /** Display the inherited fields or methods of an element.
     * This method calls itself recursively if the element has a parent class.
     * @param  elementDoc $element Link to class to generate tree for
     * @param  rootDoc    $rootDoc Link to root document
     * @param  packageDoc $package Link to current package
     * @param  string     $type    Field or method
     * @param  string     $output  Link to output array
     * @param  integer    $i       Iterator
     * @return string              Output data about inherit fields or methods
     */
    public function inherits(&$element, &$rootDoc, &$package, $type, &$output, $i) {
        $items =& $element->$type();
        if ($items) {
            ksort($items);
            $num = count($items);
            $foo = 0;
            $output[$i]['qualifiedName'] = $element->qualifiedName();
            $output[$i]['path']  = '';
            $output[$i]['name']  = '';
            $output[$i]['comma'] = '';
            foreach ($items as $item) {
                $output[$i]['path'] .= str_repeat('../', $this->_depth).$item->asPath();
                $output[$i]['name'] .= $item->name();
                if (++$foo < $num) $output[$i]['name'] .= ', ';
            }
            if ($element->superclass()) {
                $superclass =& $rootDoc->classNamed($element->superclass());
                if ($superclass) {
                    $i++;
                    $this->inherits($superclass, $rootDoc, $package, $type, $output, $i);
                }
            }
        }
    }
}
