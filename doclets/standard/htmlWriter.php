<?php
# phpAPI: The PHP Documentation Creator

/** Generate the index.html file used for presenting the frame-formated "cover page" of the API documentation.
 * @file      doclets/standard/globalWriter.php
 * @version   1.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
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
        $this->_doclet = & $doclet;
    }

    /** Build the HTML header.
     * Includes doctype definition, <html> and <head> sections, meta data and window title.
     * @param  string $title HTML page title
     * @return string        YNML page header
     */
    public function _htmlHeader($title) {
        $output  = '<!doctype html>';
        $output .= '<html lang="en">';
        $output .= '<head>';
        $output .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        $output .= '<meta name="generator" content="phpAPI '.VERSION.' (https://github.com/Greenray/phpAPI/)">';
        $output .= '<meta name="when" content="'.gmdate('r').'">';
        $output .= '<link rel="stylesheet" type="text/css" href="'.str_repeat('../', $this->_depth).'stylesheet.css">';
        $output .= '<link rel="start" href="'.str_repeat('../', $this->_depth).'overview-summary.html">';
        $output .= '<title>';
        if ($title) {
            $output .= $title.' ('.$this->_doclet->windowTitle().')';
        } else {
            $output .= $this->_doclet->windowTitle();
        }
        $output .= '</title>';
        $output .= '</head>';
        return $output;
    }

    /** Build the HTML footer.
     * @return string Closes html page
     */
    public function _htmlFooter() {
        return '</html>';
    }

    /** Build the HTML shell header.
     * Includes beginning of the <body> section, and the page header.
     * @param  string $path The path to write the file to
     * @return string       Menu for page header
     */
    public function _shellHeader($path) {
        $output  = '<body id="'.$this->_id.'" onload="parent.document.title=document.title;">';
        $output .= $this->_nav($path);
        return $output;
    }

    /** Build the HTML shell footer.
     * Includes the end of the <body> section, and page footer.
     * @param  string $path The path to write the file to
     * @return string       Menu for page footer
     */
    public function _shellFooter($path) {
        $output = $this->_nav($path);
        $output .= '<hr>';
        $output .= '<div class="footer center">'.$this->_doclet->bottom().'</div>';
        $output .= '</body>';
        return $output;
    }

    /** Build the navigation bar.
     * @param  string $path The path to write the file to
     * @return string       Navigation for documentation
     */
    public function _nav($path) {
        $output = '<div class="header">';
        $output .= '<span style="float:right">'.$this->_doclet->getHeader().'</span>';
        if ($this->_sections) {
            $output .= '<ul>';
            foreach ($this->_sections as $section) {
                if (isset($section['selected']) && $section['selected']) {
                    $output .= '<li class="active">'.$section['title'].'</li>';
                } else {
                    if (isset($section['url'])) {
                        $output .= '<li><a href="'.str_repeat('../', $this->_depth).$section['url'].'">'.$section['title'].'</a></li>';
                    } else {
                        $output .= '<li>'.$section['title'].'</li>';
                    }
                }
            }
            $output .= '</ul>';
        }
        $output .= '</div>';
        $output .= '<div class="small_links">';
        $output .= '<a href="'.str_repeat('../', $this->_depth).'index.html" target="_top">Frames</a> ';
        $output .= '<a href="'.str_repeat('../', $this->_depth).$path.'" target="_top"> No frames</a>';
        $output .= '</div>';
        $thisClass = strtolower(get_class($this));
        if ($thisClass == 'classwriter') {
            $output .= '<div class="small_links">';
            $output .= 'Summary: <a href="#summary_field">Field</a> | <a href="#summary_method">Method</a> | <a href="#summary_constr">Constr</a> ';
            $output .= 'Detail: <a href="#detail_field">Field</a> | <a href="#detail_method">Method</a> | <a href="#summary_constr">Constr</a>';
            $output .= '</div>';
        } elseif ($thisClass == 'functionwriter') {
            $output .= '<div class="small_links">';
            $output .= 'Summary: <a href="#summary_function">Function</a> ';
            $output .= 'Detail: <a href="#detail_function">Function</a>';
            $output .= '</div>';
        } elseif ($thisClass == 'globalwriter') {
            $output .= '<div class="small_links">';
            $output .= 'Summary: <a href="#summary_global">Global</a> ';
            $output .= ' etail: <a href="#detail_global">Global</a>';
            $output .= '</div>';
        }
        return $output;
    }

    public function _sourceLocation($doc) {
        if ($this->_doclet->includeSource()) {
            $url = strtolower(str_replace(DS, '/', $doc->sourceFilename()));
            echo '<a href="', str_repeat('../', $this->_depth), 'source/', $url, '.html#line', $doc->sourceLine(), '" class="location">', $doc->location(), '</a>';
        } else {
            echo '<div class="location">', $doc->location(), '</div>';
        }
    }

    /** Write the HTML page to disk using the given path.
     * @param  string  $path  The path to write the file to
     * @param  string  $title The title for this page
     * @param  boolean $shell Include the page shell in the output
     * @return void
     */
    public function _write($path, $title, $shell) {
        $phpAPI = & $this->_doclet->phpAPI();
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
                    $phpAPI->error(sprintf('Cannot create directory "%s"', $testPath));
                    exit;
                }
            }
        }
        # Write file
        $fp = fopen($this->_doclet->destinationPath().$path, 'w');
        if ($fp) {
            $phpAPI->message('Writing "'.$path.'"');
            fwrite($fp, $this->_htmlHeader($title));
            if ($shell) {
                fwrite($fp, $this->_shellHeader($path));
            }
            fwrite($fp, $this->_output);
            if ($shell) {
                fwrite($fp, $this->_shellFooter($path));
            }
            fwrite($fp, $this->_htmlFooter());
            fclose($fp);
        } else {
            $phpAPI->error('Cannot write "'.$this->_doclet->destinationPath().$path.'"');
            exit;
        }
    }

    /** Format tags for output.
     * @param Tag[]   $tags The text tag to process
     * @return string       The string representation of the elements doc tags
     */
    public function _processTags(&$tags) {
        $tagString = '';
        foreach ($tags as $key => $tag) {
            if ($key != '@text') {
                if (is_array($tag)) {
                    $hasText = FALSE;
                    foreach ($tag as $key => $tagFromGroup) {
                        if ($tagFromGroup->text($this->_doclet) !== '') {
                            $hasText = TRUE;
                        }
                    }
                    if ($hasText) {
                        $usedTag = '';
                        foreach ($tag as $tagFromGroup) {
                            $variable = explode('+', $tagFromGroup->text($this->_doclet));
                            $tagString .= '<tr>';
                            if ($tag[0]->displayName() !== $usedTag) {
                                $tagString .= '<td class="hid left w_100">'.$tag[0]->displayName().'</td>';
                            } else {
                                $tagString .= '<td class="hid left w_100">&nbsp;</td>';
                            }
                            $tagString .= '<td class="hid right w_100 lilac">'.$tagFromGroup->type().'</td>
                                           <td class="hid blue w_100">'.trim($variable[0]).'</td>';
                            if (!empty($variable[1])) {
                                $quot  = strpos($variable[1], '"');
                                $dquot = strpos($variable[1], '\'');
                                $sub   = '';
                                if ($quot === 1 || $dquot === 1) {
                                    $quot  = strrpos($variable[1], '"');
                                    $dquot = strrpos($variable[1], '\'');
                                    if (!empty($quot)) {
                                        $sub = substr($variable[1], 1, $quot);
                                    } else {
                                        if (!empty($dquot)) {
                                            $sub = substr($variable[1], 1, $dquot);
                                        }
                                    }
                                    $span    = '<span class="red">'.htmlspecialchars(trim($sub)).'</span>';
                                    $comment = str_replace($sub, $span, $variable[1]);
                                } else {
                                    $comment = htmlspecialchars(trim($variable[1]));
                                }
                                $tagString .= '<td class="hid">'.$comment.'</td>';
                            } else {
                                $tagString .= '<td class="hid">&nbsp;</td>';
                            }
                            $tagString .= '</tr>';
                            $usedTag = $tag[0]->displayName();
                        }
                    }
                } else {
                    $text = $tag->text($this->_doclet);
                    if ($text !== '') {
                        $variable = explode('+', $text);
                        $tagString .= '<tr><td class="hid left w_100">'.$tag->displayName().'</td>';
                        $type = $tag->type();
                        if (!empty($type)) {
                            $tagString .= '<td class="hid right w_100 lilac">'.$type.'</td>';
                        } else {
                            $tagString .= '<td class="hid w_100">&nbsp;</td>';
                        }
                        if (!empty($variable[1])) {
                            $tagString .= '<td class="hid blue w_100">'.trim($variable[0]).'</td>';
                            $quot  = strpos($variable[1], '"');
                            $dquot = strpos($variable[1], '\'');
                            if ($quot === 1 || $dquot === 1) {
                                $quot  = strrpos($variable[1], '"');
                                $dquot = strrpos($variable[1], '\'');
                                if (!empty($quot)) {
                                        $sub = substr($variable[1], 1, $quot);
                                } else {
                                    if (!empty($dquot)) {
                                        $sub = substr($variable[1], 1, $dquot);
                                    }
                                }
                                $span    = '<span class="red">'.htmlspecialchars(trim($sub)).'</span>';
                                $comment = str_replace($sub, $span, $variable[1]);
                            } else {
                                $comment = htmlspecialchars(trim($variable[1]));
                            }
                            $tagString .= '<td class="hid">'.$comment.'</td>';
                        } else {
                            $tagString .= '<td class="hid" colspan="2">'.$variable[0].'</td>';
                        }
                        $tagString .= '</tr>';
                    }
                }
            }
        }
        if ($tagString) {
            echo '<div id="finfo">
                    <table class="hid">'.$tagString.'</table>
                  </div>';
        }
    }

    /** Convert inline tags into a string for outputting.
     * @param Tag      $tag   The text tag to process
     * @param  boolean $first Process first line of tag only
     * @return string         The string representation of the elements doc tags
     */
    public function _processInlineTags(&$tag, $first = FALSE) {
        $description = '';
        if (is_array($tag)) {
            $tag = $tag[0];
        }
        if (is_object($tag)) {
            if ($first) {
                $tags = & $tag->firstSentenceTags($this->_doclet);
            } else {
                $tags = & $tag->inlineTags($this->_doclet);
            }
            if ($tags) {
                foreach ($tags as $aTag) {
                    if ($aTag) {
                        $description .= $aTag->text($this->_doclet);
                    }
                }
            }
            return $this->_doclet->formatter->toFormattedText($description);
        }
        return NULL;
    }
}
