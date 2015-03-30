<?php
# phpapi: The PHP Documentation Creator

/** Generate the index.html file used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @file      doclets/htmlNoFrames/globalWriter.php
 * @version   3.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class htmlWriter {

    /** The directory structure depth. Used to calculate relative paths.
     * @var integer
     */
    public $_depth = 0;

    /** The doclet that created this object.
     * @var doclet
     */
    public $_doclet;

    /** The <body> id attribute value, used for selecting style.
     * @var string
     */
    public $_id = 'overview';

    /** List of namespaces, classes, functions, globals...
     * @var string
     */
    private $items = '';

    /** The output body.
     * @var string
     */
    public $_output = '';

    /** The section titles to place in the header and footer.
     * @var array
     */
    public $_sections = [];

    /** Constructor.
     * @var doclet
     */
    public function htmlWriter(&$doclet) {
        $this->_doclet =& $doclet;
    }

    /** Builds the navigation bar.
     * @return string       Navigation for documentation
     */
    public function nav($path) {
        $output = [];
        if ($this->_sections) {
            foreach ($this->_sections as $key => $section) {
                $output['sections'][$key]['title'] = $section['title'];
                if (isset($section['selected']) && $section['selected']) {
                    $output['sections'][$key]['selected'] = $section['selected'];
                } else {
                    if (isset($section['url']))
                         $output['sections'][$key]['title'] = '<a href="'.$path.$section['url'].'">'.$section['title'].'</a>';
                    else $output['sections'][$key]['title'] = $section['title'];
                }
            }
        }
        $thisClass = get_class($this);

        $output['class']    = ($thisClass == 'classWriter')    ? TRUE : FALSE;
        $output['function'] = ($thisClass == 'functionWriter') ? TRUE : FALSE;
        $output['global']   = ($thisClass == 'globalWriter')   ? TRUE : FALSE;

        $phpapi =& $this->_doclet->phpapi();
        $tpl = new template($phpapi->getOption('doclet'), 'navigation.tpl');
        return $tpl->parse($output);
    }

    /** Writes the HTML page to disk using the given path.
     * @param  string  $path  The path to write the file to
     * @param  string  $title The title for this page
     * @param  boolean $shell Include the page shell in the output
     * @return void
     */
    public function _write($file, $title) {
        $phpapi  =& $this->_doclet->phpapi();
        $rootDoc =& $this->_doclet->rootDoc();
        # Make directory separators suitable to this platform
        $path = str_replace('/', DS, $file);

        # Make directories if they don't exist
        $dirs = explode(DS, $file);
        array_pop($dirs);
        $destPath = $this->_doclet->destinationPath();
        foreach ($dirs as $dir) {
            $destPath .= $dir.DS;
            if (!is_dir($destPath)) {
                if (!@mkdir($destPath)) {
                    $phpapi->error(sprintf('Cannot create directory "%s"', $destPath));
                    exit;
                }
            }
        }

        # Write file
        $fp = fopen($this->_doclet->destinationPath().$file, 'w');
        if ($fp) {
            $phpapi->message('Writing "'.$file.'"');
            if (!empty($dirs))
                 $output['path'] = str_repeat('../', $this->_depth);
            else $output['path'] = '';
            if ($title)
                 $output['docTitle'] = $title.' ('.$this->_doclet->docTitle().')';
            else $output['docTitle'] = $this->_doclet->docTitle();
            $output['header']    = $this->_doclet->docTitle();
            $output['allitems']  = empty($this->items) ? $this->_allItems($rootDoc, $phpapi, $output['path']) : $this->items;
            $output['headerNav'] = $this->nav($output['path']);
            $output['page']      = $this->_output;

            $tpl = new template($phpapi->getOption('doclet'), 'main.tpl');
            fwrite($fp, $tpl->parse($output));
            fclose($fp);

        } else {
            $phpapi->error('Cannot write "'.$this->_doclet->destinationPath().$file.'"');
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
                            if ($tag[0]->displayName() !== $usedTag)
                                 $output['tags'][$k]['name'] = $tag[0]->displayName();
                            else $output['tags'][$k]['name'] = '&nbsp';
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
            $tpl    = new template($phpapi->getOption('doclet'), 'tags.tpl');
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
    public function showObject($object) {
        $regexp = ["#[\"\'](.*?)[\"\']#is" => '<span class="red">\'\\1\'</span>',
                   "#<a(.*)>(.*?)</a>#is"  => '\\2'];
        $output = [];
        foreach ($object as $key => $element) {
            $output[$key]['name']      = $element->name();
            $output[$key]['modifiers'] = $element->modifiers();

            if     (method_exists($element, 'typeAsString'))       $output[$key]['type'] = $element->typeAsString();
            elseif (method_exists($element, 'returnTypeAsString')) $output[$key]['type'] = $element->returnTypeAsString();

            if (method_exists($element, 'signature')) $output[$key]['signature'] = $element->signature();
            if (method_exists($element, 'value') && !is_null($element->value())) {
                   $value = $element->value();
                   if ((strlen($value) > 100) && (substr($value, 0, 5) == 'array') || (substr($value, 0, 1) == '[') && (substr($value, -1, 1) == ']')) {
                       $value  = str_replace(["\r\n", "\n\r", "\r", "\n"], '<br />', $value);
                   }
                   $output[$key]['value'] = ' = '.preg_replace(array_keys($regexp), array_values($regexp), $value);
            } else $output[$key]['value'] = '';

            $text =& $element->tags('@text');
            if ($text) {
                $output[$key]['shortDesc'] = strip_tags($this->_processInlineTags($text, TRUE), '<a><b><strong><u><em>');
                $output[$key]['fullDesc']  = $this->_processInlineTags($text);
            } else {
                $output[$key]['shortDesc'] = __('Описания нет');
                $output[$key]['fullDesc']  = __('Описания нет');
            }
            if (method_exists($this, '_processTags')) $output[$key]['tags']     = $this->_processTags($element->tags());
            if (method_exists($element, 'location'))  $output[$key]['location'] = $element->location();
        }
        return $output;
    }

    /** Build all items frame
     * @param  rootDoc $rootDoc
     * @param  object  $phpapi
     * @param  string  $path
     * @return string           Parsed template "all-items"
     */
    function _allItems(&$rootDoc, $phpapi, $path) {
        $output = [];
        $output['header'] = $this->_doclet->getHeader();
        $output['path']   = $path;
        $packages = $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $name => $package) {
            $output['package'][$name]['path'] = $path.$package->asPath().DS;
            $output['package'][$name]['name'] = $package->name();

            $classes =& $package->ordinaryClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $i => $class) {
                    $output['class'][$i]['path']    = $path.$class->asPath();
                    $output['class'][$i]['name']    = $class->name();
                    $output['class'][$i]['package'] = $class->packageName();
                }
            }
            $interfaces =& $package->interfaces();
            if ($interfaces && is_array($interfaces)) {
                ksort($interfaces);
                foreach ($interfaces as $i => $interface) {
                    $output['interface'][$i]['path']    = $path.$interface->asPath();
                    $output['interface'][$i]['name']    = $interface->name();
                    $output['interface'][$i]['package'] = $interface->packageName();
                }
            }
            $traits =& $package->traits();
            if ($traits && is_array($traits)) {
                ksort($traits);
                foreach ($traits as $i => $trait) {
                    $output['trait'][$i]['path']    = $path.$trait->asPath();
                    $output['trait'][$i]['name']    = $trait->name();
                    $output['trait'][$i]['package'] = $trait->packageName();
                }
            }
            $exceptions =& $package->exceptions();
            if ($exceptions && is_array($exceptions)) {
                ksort($exceptions);
                foreach ($exceptions as $i => $exception) {
                    $output['exception'][$i]['path']    = $path.$exception->asPath();
                    $output['exception'][$i]['name']    = $exception->name();
                    $output['exception'][$i]['package'] = $exception->packageName();
                }
            }
            $functions =& $package->functions();
            if ($functions) {
                ksort($functions);
                foreach ($functions as $i => $function) {
                    $output['function'][$i]['path']    = $path.$function->asPath();
                    $output['function'][$i]['name']    = $function->name();
                    $output['function'][$i]['package'] = $function->packageName();
                }
            }
            $globals =& $package->globals();
            if ($globals) {
                ksort($globals);
                foreach ($globals as $i => $global) {
                    $output['global'][$i]['path']    = $path.$global->asPath();
                    $output['global'][$i]['name']    = $global->name();
                    $output['global'][$i]['package'] = $global->packageName();
                }
            }
        }
        $tpl = new template($phpapi->getOption('doclet'), 'all-items.tpl');
        return $tpl->parse($output);
    }
}
