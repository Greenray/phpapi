<?php
# phpapi: The PHP Documentation Creator

/** Generate the index.html file used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @file      doclets/standard/globalWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class htmlWriter {

    /** The doclet that created this object.
     * @var doclet
     */
    public $_doclet;

    /** The section titles to place in the header and footer.
     * @var array
     */
    public $_sections = NULL;

    /** The directory structure depth. Used to calculate relative paths.
     * @var integer
     */
    public $_depth = 0;

    /** The <body> id attribute value, used for selecting style.
     * @var string
     */
    public $_id = 'overview';

    /** The output body.
     * @var string
     */
    public $_output = '';

    /** Constructor.
     * @var doclet
     */
    public function htmlWriter(&$doclet) {
        $this->_doclet =& $doclet;
    }

    /** Builds the navigation bar.
     * @param  string $path The path to write the file to
     * @return string       Navigation for documentation
     */
    public function nav($path) {
        $output = [];
        $output['header'] = $this->_doclet->getHeader();
        if ($this->_sections) {
            foreach ($this->_sections as $key => $section) {
                $output['sections'][$key]['title'] = $section['title'];
                if (isset($section['selected']) && $section['selected']) {
                    $output['sections'][$key]['selected'] = $section['selected'];
                } else {
                    if (isset($section['url']))
                         $output['sections'][$key]['title'] = '<a href="'.str_repeat('../', $this->_depth).$section['url'].'">'.$section['title'].'</a>';
                    else $output['sections'][$key]['title'] = $section['title'];
                }
            }
        }
        $output['path'] = str_repeat('../', $this->_depth);
        $output['file'] = $path;

        $thisClass = get_class($this);

        $output['class']    = ($thisClass == 'classWriter')    ? TRUE : FALSE;
        $output['function'] = ($thisClass == 'functionWriter') ? TRUE : FALSE;
        $output['global']   = ($thisClass == 'globalWriter')   ? TRUE : FALSE;

        $phpapi =& $this->_doclet->phpapi();
        $tpl    = new template($phpapi->getOption('doclet'), 'navigation');
        return $tpl->parse($output);
    }

    /** Writes the HTML page to disk using the given path.
     * @param  string  $path  The path to write the file to
     * @param  string  $title The title for this page
     * @param  boolean $shell Include the page shell in the output
     * @return void
     */
    public function _write($path, $title, $shell) {
        $phpapi =& $this->_doclet->phpapi();

        # Make directory separators suitable to this platform
        $path = str_replace('/', DS, $path);

        # Make directories if they don't exist
        $dirs = explode(DS, $path);
        array_pop($dirs);
        $testPath = $this->_doclet->destinationPath();
        foreach ($dirs as $dir) {
            $testPath .= $dir.DS;
            if (!is_dir($testPath)) {
                if (!@mkdir($testPath)) {
                    $phpapi->error(sprintf('Cannot create directory "%s"', $testPath));
                    exit;
                }
            }
        }

        # Write file
        $fp = fopen($this->_doclet->destinationPath().$path, 'w');
        if ($fp) {
            $phpapi->message('Writing "'.$path.'"');
            if ($shell) {
                $output['headerNav'] = $this->nav($path);
                $output['footerNav'] = $output['headerNav'];
            }
            if ($title)
                 $output['title'] = $title.' ('.$this->_doclet->windowTitle().')';
            else $output['title'] = $this->_doclet->windowTitle();
            $output['path'] = str_repeat('../', $this->_depth);
            $output['page'] = $this->_output;

            $tpl = new template($phpapi->getOption('doclet'), 'main');
            fwrite($fp, $tpl->parse($output));
            fclose($fp);
        } else {
            $phpapi->error('Cannot write "'.$this->_doclet->destinationPath().$path.'"');
            exit;
        }
    }

    /** Formats tags for output.
     * @param Tag[]   $tags The text tag to process
     * @return string       The string representation of the elements doc tags
     */
    public function _processTags(&$tags) {
        $output = '';
        foreach ($tags as $key => $tag) {
            if ($key != '@text') {
                if (is_array($tag)) {
                    $hasText = FALSE;
                    foreach ($tag as $i => $tagFromGroup) {
                        if ($tagFromGroup->text($this->_doclet) !== '') $hasText = TRUE;
                    }
                    if ($hasText) {
                        $usedTag = '';
                        foreach ($tag as $k => $tagFromGroup) {
                            $variable = explode('+', $tagFromGroup->text($this->_doclet));
                            if ($tag[0]->displayName() !== $usedTag) {
                                   $output['tags'][$k]['name'] = $tag[0]->displayName();
                            } else $output['tags'][$k]['name'] = '&nbsp';
                            $output['tags'][$k]['type'] = $tagFromGroup->type();
                            $output['tags'][$k]['var']  = trim($variable[0]);
                            if (!empty($variable[1]))
                                 $output['tags'][$k]['comment'] = preg_replace("#\'(.*?)\'#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($variable[1])));
                            else $output['tags'][$k]['comment'] = '&nbsp;';

                            $usedTag = $tag[0]->displayName();
                        }
                    }
                } else {
                    $text = $tag->text($this->_doclet);
                    if ($text !== '') {
                        $variable = explode('+', $text);
                        $output['tags'][$key]['name'] = $tag->displayName();
                        $type = $tag->type();
                        if (!empty($type))
                             $output['tags'][$key]['type'] = $type;
                        else $output['tags'][$key]['type'] = '&nbsp;';
                        if (!empty($variable[1])) {
                            $output['tags'][$key]['var']     = trim($variable[0]);
                            $output['tags'][$key]['comment'] = preg_replace("#\'(.*?)\'#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($variable[1])));
                        } else {
                            $output['tags'][$key]['var']     = '';
                            $output['tags'][$key]['comment'] =$variable[0];
                        }
                    }
                }
            }
        }
        if (!empty($output)) {
            $phpapi =& $this->_doclet->phpapi();
            $tpl    = new template($phpapi->getOption('doclet'), 'tags');
            $output = $tpl->parse($output);
        }
        return $output;
    }

    /** Converts inline tags into a string for outputting.
     * @param  Tag     $tag   The text tag to process
     * @param  boolean $first Process first line of tag only
     * @return string         The string representation of the elements doc tags
     */
    public function _processInlineTags(&$tag, $first = FALSE) {
        $description = '';
        if (is_array($tag)) $tag = $tag[0];
        if (is_object($tag)) {
            if ($first)
                 $tags =& $tag->firstSentenceTags($this->_doclet);
            else $tags =& $tag->inlineTags($this->_doclet);
            if ($tags) {
                foreach ($tags as $aTag) {
                    if ($aTag) $description .= $aTag->text($this->_doclet);
                }
            }
            return $this->_doclet->formatter->toPlainText($description);
        }
        return '';
    }

    /** Preparation of the object for html template.
     * @param  object $object Object (fields, methods, constants, variables...)
     * @return array          The result
     */
    public function showObject($object, $modifiers = TRUE) {
        $string = ["#[\"\'](.*?)[\"\']#is" => '<span class="red">\'\\1\'</span>'];
        $output = [];
        foreach ($object as $key => $element) {
            $output[$key]['name']      = $element->name();
            $output[$key]['modifiers'] = $element->modifiers($modifiers);

            if     (method_exists($element, 'typeAsString'))       $output[$key]['type'] = $element->typeAsString();
            elseif (method_exists($element, 'returnTypeAsString')) $output[$key]['type'] = $element->returnTypeAsString();

            if (method_exists($element, 'signature')) $output[$key]['signature'] = $element->signature();
            if (method_exists($element, 'value') && !is_null($element->value())) {
                   $value = $element->value();
                   $output[$key]['value'] = ' = '.preg_replace(array_keys($string), array_values($string), $value);
            } else $output[$key]['value'] = '';

            $text =& $element->tags('@text');
            if ($text) {
                if (!$modifiers)
                     $output[$key]['description'] = strip_tags($this->_processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                else $output[$key]['description'] = $this->_processInlineTags($text);
            } else   $output[$key]['description'] = __('Описания нет');

            if ($modifiers && method_exists($this, '_processTags')) $output[$key]['tags']     = $this->_processTags($element->tags());
            if (method_exists($element, 'location'))                $output[$key]['location'] = $element->location();
        }
        return $output;
    }

    /** Preparation of a constant or variable for output in html template.
     * @param  mixed  $value Value of a constant or variable
     * @return string        he result
     */
    public function showValue($value) {
        $reqexp = ["#[\"\'](.*?)[\"\']#is" => '<span class="red">\'\\1\'</span>'];
        return ' = '.preg_replace(array_keys($reqexp), array_values($reqexp), $value);
    }
}
