<?php
# phpapi: The PHP Documentation Creator

/** This generates the index of elements.
 *
 * @file      doclets/standard/indexWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class indexWriter extends htmlWriter {

    /** Build the element index.
     * @param Doclet doclet
     */
    public function indexWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $rootDoc =& $this->_doclet->rootDoc();

        $this->_sections[0] = ['title' => 'Overview',   'url' => 'overview-summary.html'];
        $this->_sections[1] = ['title' => 'Namespace'];
        $this->_sections[2] = ['title' => 'Class'];
        $this->_sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->_sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->_sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->_sections[6] = ['title' => 'Index', 'selected' => TRUE];

        $classes =& $rootDoc->classes();
        if ($classes == NULL) $classes = [];
        $methods = [];
        foreach ($classes as $class) {
            foreach ($class->methods(TRUE) as $name => $method) {
                $methods[$class->name().'::'.$name] = $method;
            }
        }
        if ($methods == NULL)   $methods   = [];
        $functions =& $rootDoc->functions();
        if ($functions == NULL) $functions = [];
        $globals =& $rootDoc->globals();
        if ($globals == NULL)   $globals   = [];

        $elements = array_merge($classes, $methods, $functions, $globals);
        uasort($elements, [$this, 'compareElements']);

        ob_start();

        $letter = 64;
        foreach ($elements as $name => $element) {
            $firstChar = strtoupper(substr($element->name(), 0, 1));
            if (is_object($element) && $firstChar != chr($letter)) {
                $letter = ord($firstChar);
                echo '<a href="#letter', chr($letter), '"> ', chr($letter), '</a>';
            }
        }
        echo '<hr>';

        $first = TRUE;
        foreach ($elements as $element) {
            if (is_object($element)) {
                if (strtoupper(substr($element->name(), 0, 1)) != chr($letter)) {
                    $letter = ord(strtoupper(substr($element->name(), 0, 1)));
                    if (!$first) echo '</dl>';
                    $first = FALSE;
                    echo '<table>';
                    echo '<caption id="letter'.chr($letter).'">'.chr($letter).'</caption>';
                }
                $parent =& $element->containingClass();
                if ($parent && get_class($parent) != 'rootDoc') {
                    $in = 'class <a href="'.$parent->asPath().'">'.$parent->qualifiedName().'</a>';
                } else {
                    $package =& $element->containingPackage();
                    $in = 'namespace <a href="'.$package->asPath().'/package-summary.html">'.$package->name().'</a>';
                }
                switch (get_class($element)) {

                    case 'classDoc':
                        if ($element->isOrdinaryClass()) {
                            echo '<tr><td class="w_200"><a href="'.$element->asPath(), '">'.$element->name().'</a></td><td class="w_300">Class in '.$in.'</td>';
                        } elseif ($element->isInterface()) {
                            echo '<tr><td class="w_200"><a href="'.$element->asPath(), '">'.$element->name().'</a></td><td class="w_300">Interface in '.$in.'</td>';
                        } elseif ($element->isTrait()) {
                            echo '<tr><td class="w_200"><a href="'.$element->asPath(), '">'.$element->name().'</a></td><td class="w_300">Trait in '.$in.'</td>';
                        } elseif ($element->isException()) {
                            echo '<tr><td class="w_200"><a href="'.$element->asPath(), '">'.$element->name().'</a></td><td class="w_300">Exception in '.$in.'</td>';
                        }
                        break;

                    case 'methodDoc':
                        if ($element->isMethod()) {
                            echo '<tr><td class="w_200"><a href="'.$element->asPath(), '">'.$element->name(), '()</a></td><td class="w_300">Method in '.$in.'</td>';
                        } elseif ($element->isFunction()) {
                            echo '<tr><td class="w_200"><a href="'.$element->asPath(), '">'.$element->name(), '()</a></td><td class="w_300">Function in '.$in.'</td>';
                        }
                        break;

                    case 'fieldDoc':
                        if ($element->isGlobal()) {
                            echo '<tr><td class="w_200"><a href="'.$element->asPath(), '">'.$element->name(), '</a></td><td class="w_300">Global in '.$in.'</td>';
                        }
                        break;
                }
                if ($textTag =& $element->tags('@text') && $firstSentenceTags =& $textTag->firstSentenceTags($this->_doclet)) {
                    foreach ($firstSentenceTags as $firstSentenceTag) {
                        echo '<td>'.$firstSentenceTag->text($this->_doclet).'</td>';
                    }
                    echo '</tr>';
                } else {
                    echo '<td></td></tr>';
                }
            }
        }
        echo '</table>';

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('index-all.html', 'Index', TRUE);
    }

    public function compareElements($element1, $element2) {
        $e1 = strtolower($element1->name());
        $e2 = strtolower($element2->name());
        if ($e1 == $e2)    return 0;
        elseif ($e1 < $e2) return -1;
        else               return 1;
    }
}
