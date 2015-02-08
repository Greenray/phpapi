<?php
# phpapi: The PHP Documentation Creator

/** This generates the HTML API documentation for each individual interface and class.
 * @file      doclets/standard/classWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class classWriter extends HTMLWriter {

    /** Build the class definitons.
     * @param Doclet doclet
     */
    public function classWriter(&$doclet) {
        parent::HTMLWriter($doclet);
        $this->_id = 'definition';
        $rootDoc   = & $this->_doclet->rootDoc();
        $phpapi    = & $this->_doclet->phpapi();
        $packages  = & $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $packageName => $package) {
            $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
            $this->_sections[1] = ['title' => 'Namespace',  'url' => $package->asPath().'/package-summary.html'];
            $this->_sections[2] = ['title' => 'Class', 'selected' => TRUE];
            #$this->_sections[3] = ['title' => 'Use'];
            if ($phpapi->getOption('tree')) {
                $this->_sections[4] = ['title' => 'Tree',   'url' => $package->asPath().'/package-tree.html'];
            }
            if ($doclet->includeSource()) {
                $this->_sections[5] = ['title' => 'Files',  'url' => 'overview-files.html'];
            }
            $this->_sections[6] = ['title' => 'Deprecated', 'url' => 'deprecated-list.html'];
            $this->_sections[7] = ['title' => 'Todo',       'url' => 'todo-list.html'];
            $this->_sections[8] = ['title' => 'Index',      'url' => 'index-all.html'];

            $this->_depth = $package->depth() + 1;

            $classes = & $package->allClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $name => $class) {

                    ob_start();

                    echo '<hr>';
                    echo '<div class="qualifiedName">'.$class->qualifiedName().'</div>';
                    $this->_sourceLocation($class);
                    if ($class->isInterface()) {
                        echo '<h1>Interface '.$class->name().'</h1>';
                    } else {
                        echo '<h1>Class '.$class->name().'</h1>';
                    }
                    echo '<pre class="tree">';
                    $result = $this->_buildTree($rootDoc, $classes[$name]);
                    echo $result[0];
                    echo '</pre>';
                    $implements = & $class->interfaces();
                    if (count($implements) > 0) {
                        echo '<dl>';
                        echo '<dt>All Implemented Interfaces:</dt>';
                        echo '<dd>';
                        foreach ($implements as $interface) {
                            echo '<a href="', str_repeat('../', $this->_depth), $interface->asPath(), '">';
                            if ($interface->packageName() != $class->packageName()) {
                                echo $interface->packageName(), '\\';
                            }
                            echo $interface->name(), '</a> ';
                        }
                        echo '</dd>';
                        echo '</dl>';
                    }
                    $traits = & $class->traits();
                    if (count($traits) > 0) {
                        echo '<dl>';
                        echo '<dt>All Used Traits:</dt>';
                        echo '<dd>';
                        foreach ($traits as $trait) {
                            echo '<a href="', str_repeat('../', $this->_depth), $trait->asPath(), '">';
                            if ($trait->packageName() != $class->packageName()) {
                                echo $trait->packageName(), '\\';
                            }
                            echo $trait->name(), '</a> ';
                        }
                        echo '</dd>';
                        echo '</dl>';
                    }

                    $subclasses = $class->subclasses();
                    if ($subclasses) {
                        echo '<dl>';
                        echo '<dt>All Known Subclasses:</dt>';
                        echo '<dd>';
                        foreach ($subclasses as $subclass) {
                            echo '<a href="', str_repeat('../', $this->_depth), $subclass->asPath(), '">';
                            if ($subclass->packageName() != $class->packageName()) {
                                echo $subclass->packageName(), '\\';
                            }
                            echo $subclass->name(), '</a> ';
                        }
                        echo '</dd>';
                        echo '</dl>';
                    }
                    echo '<hr>';

                    if ($class->isInterface()) {
                        echo '<p class="signature">', $class->modifiers(), ' interface <strong>', $class->name(), '</strong>';
                    } elseif ($class->isTrait()) {
                        echo '<p class="signature">', $class->modifiers(), ' trait <strong>', $class->name(), '</strong>';
                    } else {
                        echo '<p class="signature">', $class->modifiers(), ' class <strong>', $class->name(), '</strong>';
                    }
                    if ($class->superclass()) {
                        $superclass = & $rootDoc->classNamed($class->superclass());
                        if ($superclass) {
                            echo '<br>extends <a href="', str_repeat('../', $this->_depth), $superclass->asPath(), '">', $superclass->name(), '</a>';
                        } else {
                            echo '<br>extends ', $class->superclass(), LF;
                        }
                    }
                    echo '</p>';
                    $textTag = & $class->tags('@text');
                    if ($textTag) {
                        echo '<div class="comment" id="overview_description">', $this->_processInlineTags($textTag), '</div>';
                    }
                    $this->_processTags($class->tags());

                    echo '<hr>';

                    $constants = & $class->constants();
                    ksort($constants);
                    $fields = & $class->fields();
                    ksort($fields);
                    $constructor = & $class->constructor();
                    $methods = & $class->methods(TRUE);
                    ksort($methods);

                    if ($constants) {
                        echo '<table id="summary_field">';
                        echo '<tr><th colspan="2">Constant Summary</th></tr>';
                        foreach ($constants as $field) {
                            $textTag = & $field->tags('@text');
                            echo '<tr>';
                            echo '<td class="type w_200">'.$field->modifiers(FALSE).' '.$field->typeAsString().'</td>';
                            echo '<td class="description">';
                            echo '<p class="name"><a href="#'.$field->name().'">';
                            echo '<span class="lilac">'.$field->name().'</span></a>';
                            if (!is_null($field->value())) {
                                echo $this->showValue($field->value());
                            }
                            echo '</p>';
                            if ($textTag) {
                                echo '<p class="description">'.strip_tags($this->_processInlineTags($textTag, TRUE).'<a><b><strong><u><em>'), '</p>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    }

                    if ($fields) {
                        echo '<table id="summary_field">';
                        echo '<tr><th colspan="2">Field Summary</th></tr>';
                        foreach ($fields as $field) {
                            $textTag = & $field->tags('@text');
                            echo '<tr>';
                            echo '<td class="type w_200">'.$field->modifiers(FALSE).' '.$field->typeAsString().'</td>';
                            echo '<td class="description">';
                            echo '<p class="name"><a href="#'.$field->name().'">';
                            echo '<span class="green">$'.$field->name().'</span></a>';
                            if (!is_null($field->value())) {
                                echo $this->showValue($field->value());
                            }
                            echo '</p>';
                            if ($textTag) {
                                echo '<p class="description">'.strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>').'</p>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    }

                    if ($class->superclass()) {
                        $superclass = & $rootDoc->classNamed($class->superclass());
                        if ($superclass) {
                            $this->inheritFields($superclass, $rootDoc, $package);
                        }
                    }

                    if ($constructor) {
                        echo '<table id="summary_constructor">';
                        echo '<tr><th colspan="2">Constructor Summary</th></tr>';
                        $textTag = & $constructor->tags('@text');
                        echo '<tr>';
                        echo '<td class="type w_200">'.$constructor->modifiers(FALSE).' '.$constructor->returnTypeAsString().'</td>';
                        echo '<td class="description">';
                        echo '<p class="name"><a href="#'.$constructor->name().'()"><strong><span class="black">'.$constructor->name().'</span></strong></a>'.$constructor->flatSignature().'</p>';
                        if ($textTag) {
                            echo '<p class="description">'.strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>').'</p>';
                        }
                        echo '</td>';
                        echo '</tr>';
                        echo '</table>';
                    }

                    if ($methods) {
                        echo '<table id="summary_method">';
                        echo '<tr><th colspan="2">Method Summary</th></tr>';
                        foreach ($methods as $method) {
                            $textTag = & $method->tags('@text');
                            echo '<tr>';
                            echo '<td class="type w_200">'.$method->modifiers(FALSE).' '.$method->returnTypeAsString().'</td>';
                            echo '<td class="description">';
                            echo '<p class="name"><a href="#'.$method->name().'()"><strong><span class="black">'.$method->name().'</span></strong></a>'.$method->flatSignature().'</p>';
                            if ($textTag) {
                                echo '<p class="description">'.strip_tags($this->_processInlineTags($textTag, TRUE), '<a><b><strong><u><em>').'</p>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    }

                    if ($class->superclass()) {
                        $superclass = & $rootDoc->classNamed($class->superclass());
                        if ($superclass) {
                            $this->inheritMethods($superclass, $rootDoc, $package);
                        }
                    }

                    if ($constants) {
                        echo '<h2 id="detail_field">Constant Detail</h2>';
                        foreach ($constants as $field) {
                            $textTag = & $field->tags('@text');
                            $type = & $field->type();
                            $this->_sourceLocation($field);
                            echo '<h3 id="', $field->name(), '">', $field->name(), '</h3>';
                            echo '<code class="signature">', $field->modifiers(), ' ', $field->typeAsString(), ' <strong>';
//                            if (is_null($field->constantValue())) {}
                            echo $field->name(), '</strong>';
                            if (!is_null($field->value())) {
                                echo $this->showValue($field->value());
                            }
                            echo '</code>';
                            echo '<div class="details">';
                            if ($textTag) {
                                echo $this->_processInlineTags($textTag);
                            }
                            $this->_processTags($field->tags());
                            echo '</div>';
                            echo '<hr>';
                        }
                    }

                    if ($fields) {
                        echo '<h2 id="detail_field">Field Detail</h2>';
                        foreach ($fields as $field) {
                            $textTag = & $field->tags('@text');
                            $type = & $field->type();
                            $this->_sourceLocation($field);
                            echo '<code class="signature" id="', $field->name(), '">'.$field->modifiers().' '.$field->typeAsString().' ';
                            if (is_null($field->constantValue())) {
                                echo '<span class="green">$'.$field->name().'</span>';
                            } else {
                                echo '<span class="lilac">'.$field->name().'</span>';
                            }
                            echo '</strong>';
                            if (!is_null($field->value())) {
                                echo $this->showValue($field->value());
                            }
                            echo '</code>';
                            echo '<div class="details">';
                            if ($textTag) {
                                echo $this->_processInlineTags($textTag);
                            }
                            $this->_processTags($field->tags());
                            echo '</div>';
                            echo '<hr>';
                        }
                    }

                    if ($constructor) {
                        echo '<h2 id="detail_method">Constructor Detail</h2>';
                        $textTag = & $constructor->tags('@text');
                        $this->_sourceLocation($constructor);
                        echo '<code class="signature" id="', $constructor->name(), '()">', $constructor->modifiers(), ' ', $constructor->returnTypeAsString(), ' <strong>';
                        echo $constructor->name(), '</strong>', $constructor->flatSignature();
                        echo '</code>';
                        echo '<div class="details">';
                        if ($textTag) {
                            echo $this->_processInlineTags($textTag);
                        }
                        $this->_processTags($constructor->tags());
                        echo '</div>';
                        echo '<hr>';
                    }

                    if ($methods) {
                        echo '<h2 id="detail_method">Method Detail</h2>';
                        foreach ($methods as $method) {
                            $textTag = & $method->tags('@text');
                            $this->_sourceLocation($method);
                            echo '<code class="signature" id="', $method->name(), '()">', $method->modifiers(), ' ', $method->returnTypeAsString(), ' <strong>';
                            echo $method->name(), '</strong>', $method->flatSignature();
                            echo '</code>';
                            echo '<div class="details">';
                            if ($textTag) {
                                echo $this->_processInlineTags($textTag);
                            }
                            $this->_processTags($method->tags());
                            echo '</div>';
                            echo '<hr>';
                        }
                    }
                    $this->_output = ob_get_contents();
                    ob_end_clean();

                    $this->_write($package->asPath().'/'.strtolower($class->name()).'.html', $class->name(), TRUE);
                }
            }
        }
    }

    /** Build the class hierarchy tree which is placed at the top of the page.
     * @param RootDoc rootDoc The root doc
     * @param ClassDoc class Class to generate tree for
     * @param int depth Depth of recursion
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
            $superclass = & $rootDoc->classNamed($class->superclass());
            if ($superclass) {
                $result = $this->_buildTree($rootDoc, $superclass, $depth);
                $output .= $result[0];
                $depth = ++$result[1];
            } else {
                $output .= $class->superclass().'<br>';
                $output .= str_repeat('   ', $depth).' └─';
                $depth++;
                $undefinedClass = TRUE;
            }
        }
        if ($depth > 0 && !$undefinedClass) {
            $output .= str_repeat('   ', $depth).' └─';
        }
        if ($start) {
            $output .= '<strong>'.$class->name().'</strong><br>';
        } else {
            $output .= '<a href="'.str_repeat('../', $this->_depth).$class->asPath().'">'.$class->name().'</a><br>';
        }
        return [$output, $depth];
    }

    /** Display the inherited fields of an element.
     * This method calls itself recursively if the element has a parent class.
     * @param ProgramElementDoc element
     * @param RootDoc rootDoc
     * @param PackageDoc package
     */
    public function inheritFields(&$element, &$rootDoc, &$package) {
        $fields = & $element->fields();
        if ($fields) {
            ksort($fields);
            $num = count($fields);
            $foo = 0;
            echo '<table class="inherit">';
            echo '<tr><th colspan="2">Fields inherited from ', $element->qualifiedName(), '</th></tr>';
            echo '<tr><td>';
            foreach ($fields as $field) {
                echo '<a href="', str_repeat('../', $this->_depth), $field->asPath(), '">', $field->name(), '</a>';
                if (++$foo < $num) {
                    echo ', ';
                }
            }
            echo '</td></tr>';
            echo '</table>';
            if ($element->superclass()) {
                $superclass = & $rootDoc->classNamed($element->superclass());
                if ($superclass) {
                    $this->inheritFields($superclass, $rootDoc, $package);
                }
            }
        }
    }

    /** Display the inherited methods of an element.
     * This method calls itself recursively if the element has a parent class.
     * @param ProgramElementDoc element
     * @param RootDoc rootDoc
     * @param PackageDoc package
     */
    public function inheritMethods(&$element, &$rootDoc, &$package) {
        $methods = & $element->methods();
        if ($methods) {
            ksort($methods);
            $num = count($methods);
            $foo = 0;
            echo '<table class="inherit">';
            echo '<tr><th colspan="2">Methods inherited from ', $element->qualifiedName(), '</th></tr>';
            echo '<tr><td>';
            foreach ($methods as $method) {
                echo '<a href="', str_repeat('../', $this->_depth), $method->asPath(), '">', $method->name(), '</a>';
                if (++$foo < $num) {
                    echo ', ';
                }
            }
            echo '</td></tr>';
            echo '</table>';
            if ($element->superclass()) {
                $superclass = & $rootDoc->classNamed($element->superclass());
                if ($superclass) {
                    $this->inheritMethods($superclass, $rootDoc, $package);
                }
            }
        }
    }

    private function showValue($value) {
        $result = ' = ';
        $quot   = strpos($value, '"');
        $dquot  = strpos($value, '\'');
        if ($quot === 0 || $dquot === 0) {
            $result .= '<span class="red">'.htmlspecialchars($value).'</span>';
        } else {
            $quot  = substr($value, 0, 1);
            $start = 0;
            if ($quot == '[') {
                $dquot = substr($value, 1, -1);
                $start = 1;
            } else {
                $quot = substr($value, 5, 1);
                if ($quot == '(') {
                    $dquot = substr($value, 6, -1);
                    $start = 6;
                }
            }
            $result .= substr_replace($value, '<span class="red">'.htmlspecialchars($dquot).'</span>', $start, strlen($dquot));
        }
        return $result;
    }
}
