<?php
/** Generate the index.html file used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlFrames/globalWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlFrames
 */

class htmlWriter {

    /** The directory structure depth. Used to calculate relative paths.
     * @var integer
     */
    public $depth = 0;

    /** The doclet that created this object.
     * @var doclet
     */
    public $doclet;

    /** The <body> id attribute value, used for selecting style.
     * @var string
     */
    public $id = 'overview';

    /** The output body.
     * @var string
     */
    public $output = '';

    /** The section titles to place in the header and footer.
     * @var array
     */
    public $sections = [];

    /** Constructor.
     *
     * @var doclet
     */
    public function htmlWriter(&$doclet) {
        $this->doclet = &$doclet;
    }

    /** Builds the navigation bar.
     *
     * @param  string $path The path to write the file to
     * @return string       Navigation for documentation
     */
    public function nav($path, $file) {
        $output = [];
        $output['header'] = $this->doclet->getHeader();
        $output['path']   = $path;
        $output['file']   = $file;
        if ($this->sections) {
            foreach ($this->sections as $key => $section) {
                $output['section'][$key]['title'] = $section['title'];
                if (isset($section['selected']) && $section['selected']) {
                    $output['section'][$key]['selected'] = $section['selected'];
                } else {
                    if (isset($section['url']))
                         $output['section'][$key]['title'] = '<a href="'.$path.$section['url'].'">'.$section['title'].'</a>';
                    else $output['section'][$key]['title'] = $section['title'];
                }
            }
        }
        $thisClass = get_class($this);

        $output['class']    = ($thisClass == 'classWriter')    ? TRUE : FALSE;
        $output['function'] = ($thisClass == 'functionWriter') ? TRUE : FALSE;
        $output['global']   = ($thisClass == 'globalWriter')   ? TRUE : FALSE;

        $phpapi = &$this->doclet->phpapi();
        $tpl = new template($phpapi->options['doclet'], 'navigation.tpl');
        return $tpl->parse($output);
    }

    /** Writes the HTML page to disk using the given path.
     *
     * @param  string  $file  The path to write the file to
     * @param  string  $title The title for this page
     * @param  boolean $shell Include the page shell in the output
     * @return void
     */
    protected function write($file, $title, $shell = TRUE) {
        $phpapi = &$this->doclet->phpapi();

        # Make directory separators suitable to this platform
        $file = str_replace('/', DS, $file);

        # Make directories if they don't exist
        $dirs = explode(DS, $file);
        array_pop($dirs);
        $destPath = $this->doclet->destination;
        foreach ($dirs as $dir) {
            $destPath .= $dir.DS;
            if (!is_dir($destPath)) {
                if (!@mkdir($destPath)) {
                    $phpapi->error('Cannot create directory '.$destPath);
                    exit;
                }
            }
        }

        # Write file
        $fp = fopen($this->doclet->destination.$file, 'w');
        if ($fp) {
            $phpapi->verbose('Writing "'.$file.'"');
            $output['id'] = $this->id;
            if (!empty($dirs))
                 $output['path'] = str_repeat('../', $this->depth);
            else $output['path'] = '';
            if ($shell) {
                $output['headerNav'] = $this->nav($output['path'], $file);
                $output['footerNav'] = $output['headerNav'];
            }
            if ($title)
                 $output['docTitle'] = $title.' ('.$phpapi->options['docTitle'].')';
            else $output['docTitle'] = $phpapi->options['docTitle'];
            $output['page'] = $this->output;

            $tpl = new template($phpapi->options['doclet'], 'main.tpl');
            fwrite($fp, $tpl->parse($output));
            fclose($fp);

        } else {
            $phpapi->error('Cannot write "'.$this->doclet->destination.$file.'"');
            exit;
        }
    }

    /** Formats tags for output.
     *
     * @param Tag[]   $tags The text tag to process
     * @return string       The string representation of the elements doc tags
     */
    protected function processTags(&$tags) {
        $output = '';
        foreach ($tags as $key => $tag) {
            if ($key != '@text') {
                if (is_array($tag)) {
                    $hasText = FALSE;
                    foreach ($tag as $i => $tagFromGroup) {
                        if ($tagFromGroup->text($this->doclet) !== '') $hasText = TRUE;
                    }
                    if ($hasText) {
                        $usedTag = '';
                        foreach ($tag as $k => $tagFromGroup) {
                            $variable = explode('+', $tagFromGroup->text($this->doclet));
                            if ($tag[0]->displayName() != $usedTag)
                                 $output['tag'][$k]['name'] = $tag[0]->displayName();
                            else $output['tag'][$k]['name'] = '&nbsp';
                            $output['tag'][$k]['type'] = $tagFromGroup->type;
                            $output['tag'][$k]['var']  = trim($variable[0]);
                            if (!empty($variable[1]))
                                 $output['tag'][$k]['comment'] = preg_replace("#\'(.*?)\'#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($variable[1])));
                            else $output['tag'][$k]['comment'] = '&nbsp;';
                            $usedTag = $tag[0]->displayName();
                        }
                    }
                } else {
                    $text = $tag->text($this->doclet);
                    if ($text !== '') {
                        $variable = explode('+', $text);
                        $output['tag'][$key]['name'] = $tag->displayName();
                        $type = $tag->type;
                        if (!empty($type))
                             $output['tag'][$key]['type'] = $type;
                        else $output['tag'][$key]['type'] = '&nbsp;';
                        if (!empty($variable[1])) {
                             $output['tag'][$key]['var']     = trim($variable[0]);
                             $output['tag'][$key]['comment'] = preg_replace("#\'(.*?)\'#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($variable[1])));
                        } else {
                             $output['tag'][$key]['var']     = '';
                             $output['tag'][$key]['comment'] =$variable[0];
                        }
                    }
                }
            }
        }
        if (!empty($output)) {
            $phpapi = &$this->doclet->phpapi();
            $tpl    = new template($phpapi->options['doclet'], 'tags.tpl');
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
    protected function processInlineTags(&$tag, $first = FALSE) {
        $description = '';
        if (is_array($tag)) $tag = $tag[0];
        if (is_object($tag)) {
            if ($first)
                 $tags = &$tag->firstSentenceTags($this->doclet);
            else $tags = &$tag->inlineTags($this->doclet);
            if ($tags) {
                foreach ($tags as $aTag) {
                    if ($aTag) $description .= $aTag->text($this->doclet);
                }
            }
            return $this->doclet->formatter->toPlainText($description);
        }
        return '';
    }

    /** Preparation of the object for html template.
     *
     * @param  object $object Object (fields, methods, constants, variables...)
     * @return array          The result
     */
    public function showObject($object) {
        $regexp = ["#[\"\'](.*?)[\"\']#is" => '<span class="red">\'\\1\'</span>',
                   "#<a(.*)>(.*?)</a>#is"  => '\\2'];
        $output  = [];
        foreach ($object as $key => $element) {
            $output[$key]['name']      = $element->name();
            $output[$key]['modifiers'] = $element->modifiers();

            if     (method_exists($element, 'typeAsString'))       $output[$key]['type'] = $element->typeAsString();
            elseif (method_exists($element, 'returnTypeAsString')) $output[$key]['type'] = $element->returnTypeAsString();

            if (method_exists($element, 'signature')) $output[$key]['signature'] = $element->signature();
            if (isset($element->value) && !is_null($element->value)) {
                   $value = $element->value;
                   if ((strlen($value) > 100) && (substr($value, 0, 5) == 'array') || (substr($value, 0, 1) == '[') && (substr($value, -1, 1) == ']')) {
                       $value  = str_replace(["\r\n", "\n\r", "\r", "\n"], '<br />', $value);
                   }
                   $output[$key]['value'] = ' = '.preg_replace(array_keys($regexp), array_values($regexp), $value);
            } else $output[$key]['value'] = '';

            $text = &$element->tags('@text');
            if ($text) {
                $output[$key]['shortDesc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                $output[$key]['fullDesc']  = $this->processInlineTags($text);
            } else {
                $output[$key]['shortDesc'] = __('Описания нет');
                $output[$key]['fullDesc']  = __('Описания нет');
            }
            if (method_exists($this, 'processTags')) $output[$key]['tags']     = $this->processTags($element->tags());
            if (method_exists($element, 'location')) $output[$key]['location'] = $element->location();
        }
        return $output;
    }
}
