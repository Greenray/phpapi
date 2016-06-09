<?php
/**
 * This generates the index of elements.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/indexWriter.php
 * @package   html
 */

class indexWriter extends htmlWriter {

    /**
     * Builds the index of elements.
     *
     * @param doclet &$doclet Reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $this->sections[0] = ['title' => 'Overview',   'url' => $index.'.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index', 'selected' => TRUE];

        $classes = &$doclet->rootDoc->classes();
        if ($classes === NULL) $classes = [];
        $methods = [];
        foreach ($classes as $class) {
            foreach ($class->methods() as $name => $method) {
                $methods[$class->name.'::'.$name] = $method;
            }
        }
        unset($name, $method);
        if ($methods === NULL)   $methods   = [];
        $functions = &$doclet->rootDoc->functions();
        if ($functions === NULL) $functions = [];
        $globals   = &$doclet->rootDoc->globals();
        if ($globals === NULL)   $globals   = [];

        $elements = array_merge($classes, $methods, $functions, $globals);
        unset($classes, $methods, $functions, $globals);
        uasort($elements, [$this, 'compareElements']);

        $output  = [];
        $letter  = '';
        $letters = [];
        $tpl = new template();
        foreach ($elements as $i => $element) {
            if (is_object($element)) {
                $firstChar = strtoupper(substr($element->name, 0, 1));
                if ($firstChar !== $letter) {
                    $letter = $firstChar;
                    $letters[$i]['letter']   = $letter;
                    $output[$letter]['char'] = $letter;
                }

                $parent = &$element->containingClass();
                if ($parent && get_class($parent) !=='rootDoc') {
                    $output[$letter]['letter'][$i]['in']     = __('of class');
                    $output[$letter]['letter'][$i]['inPath'] = $parent->path();
                    $output[$letter]['letter'][$i]['inName'] = $parent->fullNamespace();
                } else {
                    $package = &$element->containingPackage();
                    $output[$letter]['letter'][$i]['in']     = __('in namespace');
                    $output[$letter]['letter'][$i]['inPath'] = $package->path().DS.'package-summary.html';
                    $output[$letter]['letter'][$i]['inName'] = $package->name;
                }

                switch (get_class($element)) {

                    case 'classDoc':
                        if     ($element->isOrdinaryClass()) $output[$letter]['letter'][$i]['element'] = __('Class');
                        elseif ($element->isInterface())     $output[$letter]['letter'][$i]['element'] = __('Interface');
                        elseif ($element->isTrait())         $output[$letter]['letter'][$i]['element'] = __('Trait');
                        elseif ($element->isException())     $output[$letter]['letter'][$i]['element'] = __('Exception');
                        break;

                    case 'methodDoc':
                        if     (!$element->isFunction())   $output[$letter]['letter'][$i]['element'] = __('Method');
                        elseif ($element->isConstructor()) $output[$letter]['letter'][$i]['element'] = __('Constructor');
                        elseif ($element->isDestructor())  $output[$letter]['letter'][$i]['element'] = __('Destructor');
                        elseif ($element->isFunction())    $output[$letter]['letter'][$i]['element'] = __('Function');
                        break;

                    case 'fieldDoc':
                        if ($element->isGlobal()) $output[$letter]['letter'][$i]['element'] = __('Global');
                        break;
                }

                $output[$letter]['letter'][$i]['path'] = $element->path();
                $output[$letter]['letter'][$i]['name'] = $element->name;
                if ($textTag = &$element->tags['@text'] && $firstCommentString = &$textTag->firstCommentString())
                     $output[$letter]['letter'][$i]['description'] = $firstCommentString[0]->text;
                else $output[$letter]['letter'][$i]['description'] = __('No description');
            }
        }
        $tpl->set('letters',  $letters);
        $tpl->set('elements', $output);

        $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'index-all');
        $this->write('index-all.html', 'Index');
    }

    /**
     * Compares two elements.
     *
     * @param  mixed $element1 Element 1 to compare
     * @param  mixed $element2 Element 2 to comar
     * @return integer         Resalt of compare
     */
    public function compareElements($element1, $element2) {
        $e1 = strtolower($element1->name);
        $e2 = strtolower($element2->name);
        if ($e1 === $e2)   return 0;
        elseif ($e1 < $e2) return -1;
        else               return 1;
    }
}
