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

    /** Writer constructor.
     * @var doclet
     */
    public function htmlWriter(&$doclet) {
        $this->_doclet =& $doclet;
    }

    /** Builds the HTML header.
     * Includes doctype definition, <html> and <head> sections, meta data and window title.
     *
     * @param  string $title HTML page title
     * @return string        YNML page header
     */
    public function _htmlHeader($title) {
        $output  =
            '<!doctype html>
             <html lang="en">
             <head>
             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
             <meta name="generator" content="phpapi '.VERSION.' (https://github.com/Greenray/phpapi/)">
             <link rel="stylesheet" type="text/css" href="'.str_repeat('../', $this->_depth).'stylesheet.css">
             <link rel="start" href="'.str_repeat('../', $this->_depth).'overview-summary.html">
             <title>';
        if ($title)
             $output .= $title.' ('.$this->_doclet->windowTitle().')';
        else $output .= $this->_doclet->windowTitle();
        $output .= '</title>
                </head>';
        return $output;
    }

    /** Builds the HTML footer.
     * @return string Closes html page
     */
    public function _htmlFooter() {
        return '</html>';
    }

    /** Builds the HTML shell header.
     * Includes beginning of the <body> section, and the page header.
     *
     * @param  string $path The path to write the file to
     * @return string       Menu for page header
     */
    public function _shellHeader($path) {
        $output  = '<body id="'.$this->_id.'" onload="parent.document.title=document.title;">';
        $output .= $this->_nav($path);

        return $output;
    }

    /** Builds the HTML shell footer.
     * Includes the end of the <body> section, and page footer.
     *
     * @param  string $path The path to write the file to
     * @return string       Menu for page footer
     */
    public function _shellFooter($path) {
        $output = $this->_nav($path);
        $output .= '<hr>
                    <div class="footer center">'.$this->_doclet->bottom().'</div>
                 </body>';
        return $output;
    }

    /** Builds the navigation bar.
     *
     * @param  string $path The path to write the file to
     * @return string       Navigation for documentation
     */
    public function _nav($path) {
        $output = '<div class="header">
                       <span style="float:right">'.$this->_doclet->getHeader().'</span>';
        if ($this->_sections) {
            $output .= '<ul>';
            foreach ($this->_sections as $section) {
                if (isset($section['selected']) && $section['selected']) {
                    $output .= '<li class="active">'.$section['title'].'</li>';
                } else {
                    if (isset($section['url']))
                         $output .= '<li><a href="'.str_repeat('../', $this->_depth).$section['url'].'">'.$section['title'].'</a></li>';
                    else $output .= '<li>'.$section['title'].'</li>';
                }
            }
            $output .= '</ul>';
        }
        $output .= '</div>
                        <div class="small_links">
                            <a href="'.str_repeat('../', $this->_depth).'index.html" target="_top">Frames </a>
                                < >
                            <a href="'.str_repeat('../', $this->_depth).$path.'" target="_top"> No frames</a>
                        </div>';
        $thisClass = get_class($this);
        if ($thisClass == 'classWriter') {
            $output .= '<div class="small_links">
                            Summary: <a href="#summary_fields">Fields</a> | <a href="#summary_methods">Methods</a> | <a href="#summary_constructor">Constructor</a>
                            Details: <a href="#details_fields">Fields</a> | <a href="#details_methods">Methods</a> | <a href="#details_constructor">Constructor</a>
                        </div>';
        } elseif ($thisClass == 'functionWriter') {
            $output .= '<div class="small_links">
                            Summary: <a href="#summary_functions">Functions</a>
                            Details: <a href="#details_functions">Functions</a>
                        </div>';
        } elseif ($thisClass == 'globalWriter') {
            $output .= '<div class="small_links">
                            Summary: <a href="#summary_globals">Globals</a>
                            Details: <a href="#details_globals">Globals</a>
                        </div>';
        }

        return $output;
    }

    /** Location of the source file.
     *
     * @param  object $doc Object of the current source file
     * @return string      Link to the line of the source file
     */
    public function _sourceLocation($doc) {
        return $doc->location();
    }

    /** Writes the HTML page to disk using the given path.
     *
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

            fwrite($fp, $this->_htmlHeader($title));
            if ($shell) fwrite($fp, $this->_shellHeader($path));

            fwrite($fp, $this->_output);
            if ($shell) fwrite($fp, $this->_shellFooter($path));

            fwrite($fp, $this->_htmlFooter());
            fclose($fp);

        } else {
            $phpapi->error('Cannot write "'.$this->_doclet->destinationPath().$path.'"');
            exit;
        }
    }

    /** Formats tags for output.
     *
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
            $tpl = new template($phpapi->getOption('doclet'), 'tags');
            $output = $tpl->parse($output);
        }
        return $output;
    }

    /** Converts inline tags into a string for outputting.
     *
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
        return NULL;
    }

    /** Preparation of the object for html template.
     *
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
            if (method_exists($this, '_sourceLocation'))            $output[$key]['location'] = $this->_sourceLocation($element);
        }
        return $output;
    }

    /** Preparation of a constant or variable for output in html template.
     *
     * @param  mixed  $value Value of a constant or variable
     * @return string        he result
     */
    public function showValue($value) {
        $reqexp = ["#[\"\'](.*?)[\"\']#is" => '<span class="red">\'\\1\'</span>'];
        return ' = '.preg_replace(array_keys($reqexp), array_values($reqexp), $value);
    }
}
