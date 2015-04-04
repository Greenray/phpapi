<?php
/** This generates the index of elements.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlNoFrames/indexWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class indexWriter extends htmlWriter {

    /** Build the element index.
     *
     * @param Doclet doclet
     */
    public function indexWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $this->sections[0] = ['title' => 'Overview',   'url' => 'index.html'];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index', 'selected' => TRUE];

        $rootDoc = &$this->doclet->rootDoc();
        $phpapi  = &$this->doclet->phpapi();
        $classes = &$rootDoc->classes();
        if ($classes == NULL) $classes = [];
        $methods = [];
        foreach ($classes as $class) {
            foreach ($class->methods(TRUE) as $name => $method) {
                $methods[$class->name().'::'.$name] = $method;
            }
        }
        if ($methods == NULL)   $methods   = [];
        $functions = &$rootDoc->functions();
        if ($functions == NULL) $functions = [];
        $globals = &$rootDoc->globals();
        if ($globals == NULL)   $globals   = [];

        $elements = array_merge($classes, $methods, $functions, $globals);
        uasort($elements, [$this, 'compareElements']);

        $output = [];
        $letter = '';
        foreach ($elements as $i => $element) {
            if (is_object($element)) {
                $firstChar = strtoupper(substr($element->name(), 0, 1));
                if ($firstChar != $letter) {
                    $letter = $firstChar;
                    $output['letters'][$i]['letter'] = $letter;
                    $output['elements'][$letter]['char'] = $letter;
                }

                $parent = &$element->containingClass();
                if ($parent && get_class($parent) != 'rootDoc') {
                    $output['elements'][$letter]['letter'][$i]['in']     = __('класса');
                    $output['elements'][$letter]['letter'][$i]['inPath'] = $parent->asPath();
                    $output['elements'][$letter]['letter'][$i]['inName'] = $parent->qualifiedName();
                } else {
                    $package = &$element->containingPackage();
                    $output['elements'][$letter]['letter'][$i]['in']     = __('в пространстве имен');
                    $output['elements'][$letter]['letter'][$i]['inPath'] = $package->asPath().DS.'package-summary.html';
                    $output['elements'][$letter]['letter'][$i]['inName'] = $package->name();
                }
                switch (get_class($element)) {

                    case 'classDoc':
                        if     ($element->isOrdinaryClass()) $output['elements'][$letter]['letter'][$i]['element'] = __('Класс');
                        elseif ($element->isInterface())     $output['elements'][$letter]['letter'][$i]['element'] = __('Интерфейс');
                        elseif ($element->isTrait())         $output['elements'][$letter]['letter'][$i]['element'] = __('Трейт');
                        elseif ($element->isException())     $output['elements'][$letter]['letter'][$i]['element'] = __('Исключение');
                        break;

                    case 'methodDoc':
                        if     ($element->isMethod())   $output['elements'][$letter]['letter'][$i]['element'] = __('Метод');
                        elseif ($element->isFunction()) $output['elements'][$letter]['letter'][$i]['element'] = __('Функция');
                        break;

                    case 'fieldDoc':
                        if ($element->isGlobal()) $output['elements'][$letter]['letter'][$i]['element'] = __('Глобальный элемент');
                        break;
                }

                $output['elements'][$letter]['letter'][$i]['path'] = $element->asPath();
                $output['elements'][$letter]['letter'][$i]['name'] = $element->name();
                if ($textTag = &$element->tags('@text') && $firstSentenceTags = &$textTag->firstSentenceTags($this->doclet)) {
                    foreach ($firstSentenceTags as $firstSentenceTag) {
                       $output['elements'][$letter]['letter'][$i]['description'] = $firstSentenceTag->text($this->doclet);
                    }
                } else $output['elements'][$letter]['letter'][$i]['description'] = __('Описания нет');
            }
        }

        $tpl = new template($phpapi->options['doclet'], 'index-all.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('index-all.html', 'Index');
    }

    /** Compares two elements.
     *
     * @param  mixed   $element1 The first element
     * @param  mixed   $element2 The second element
     * @return integer           The result
     */
    public function compareElements($element1, $element2) {
        $e1 = strtolower($element1->name());
        $e2 = strtolower($element2->name());
        if ($e1 == $e2)    return 0;
        elseif ($e1 < $e2) return -1;
        else               return 1;
    }
}
