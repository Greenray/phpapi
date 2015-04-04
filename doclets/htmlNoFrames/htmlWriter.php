<?php
/** Generate the index.html file used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlNoFrames/globalWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
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

    /** List of all namespaces, classes, functions, globals...
     * @var string
     */
    public $allItems = '';

    /** List of package, functions, globals...
     * @var string
     */
    public $items = '';

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
     * @param  string $path Path to section
     * @return string       Navigation for documentation
     */
    public function nav($path) {
        $output = [];
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
     * @param  string  $path  The path to write the file to
     * @param  string  $title The title for this page
     * @param  boolean $shell Include the page shell in the output
     * @return void
     */
    protected function write($file, $title) {
        $phpapi  = &$this->doclet->phpapi();
        $rootDoc = &$this->doclet->rootDoc();
        # Make directory separators suitable to this platform
        $path = str_replace('/', DS, $file);

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

            if (!empty($dirs))
                 $output['path'] = str_repeat('../', $this->depth);
            else $output['path'] = '';

            if ($title)
                 $output['docTitle'] = $title.' ('.$phpapi->options['docTitle'].')';
            else $output['docTitle'] = $phpapi->options['docTitle'];
            $output['header'] = $phpapi->options['docTitle'];

            if (!empty($this->items))
                 $output['items'] = $this->items;
            else $output['items'] = empty($this->allItems) ? $this->allItems($rootDoc, $phpapi, $output['path']) : $this->allItems;

            $output['headerNav']  = $this->nav($output['path']);
            $output['page']       = $this->output;
            $this->items = '';

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
        $output = [];
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
            if (method_exists($element, 'location'))  $output[$key]['location'] = $element->location();
        }
        return $output;
    }

    /** Builds all items section.
     *
     * @param  rootDoc $rootDoc Reference to rootDoc
     * @param  object  $phpapi  phpapi object
     * @param  string  $path    Path to directory for output
     * @return string           Parsed template "all-items"
     */
    private function allItems(&$rootDoc, $phpapi, $path) {
        $output = [];
        $output['header'] = $this->doclet->getHeader();
        $output['path']   = $path;
        $packages = $rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $output['package'][$name]['path'] = $path.$package->asPath().DS;
            $output['package'][$name]['name'] = $package->name();

            $classes = &$package->ordinaryClasses();
            if ($classes) {
                ksort($classes);
                foreach ($classes as $i => $class) {
                    $output['class'][$i]['path']    = $path.$class->asPath();
                    $output['class'][$i]['name']    = $class->name();
                    $output['class'][$i]['package'] = $class->packageName();
                }
            }

            $interfaces = &$package->interfaces();
            if ($interfaces && is_array($interfaces)) {
                ksort($interfaces);
                foreach ($interfaces as $i => $interface) {
                    $output['interface'][$i]['path']    = $path.$interface->asPath();
                    $output['interface'][$i]['name']    = $interface->name();
                    $output['interface'][$i]['package'] = $interface->packageName();
                }
            }

            $traits = &$package->traits();
            if ($traits && is_array($traits)) {
                ksort($traits);
                foreach ($traits as $i => $trait) {
                    $output['trait'][$i]['path']    = $path.$trait->asPath();
                    $output['trait'][$i]['name']    = $trait->name();
                    $output['trait'][$i]['package'] = $trait->packageName();
                }
            }

            $exceptions = &$package->exceptions();
            if ($exceptions && is_array($exceptions)) {
                ksort($exceptions);
                foreach ($exceptions as $i => $exception) {
                    $output['exception'][$i]['path']    = $path.$exception->asPath();
                    $output['exception'][$i]['name']    = $exception->name();
                    $output['exception'][$i]['package'] = $exception->packageName();
                }
            }

            $functions = &$package->functions();
            if ($functions) {
                ksort($functions);
                foreach ($functions as $i => $function) {
                    $output['function'][$i]['path']    = $path.$function->asPath();
                    $output['function'][$i]['name']    = $function->name();
                    $output['function'][$i]['package'] = $function->packageName();
                }
            }

            $globals = &$package->globals();
            if ($globals) {
                ksort($globals);
                foreach ($globals as $i => $global) {
                    $output['global'][$i]['path']    = $path.$global->asPath();
                    $output['global'][$i]['name']    = $global->name();
                    $output['global'][$i]['package'] = $global->packageName();
                }
            }
        }

        $tpl = new template($phpapi->options['doclet'], 'all-items.tpl');
        $this->items = $tpl->parse($output);
        return $this->items;
    }

    /** Builds all items of the class.
     *
     * @param  object $phpapi  phpapi object
     * @param  object $package Reference to current package
     * @return string          Parsed template "class-items"
     */
    protected function packageItems($phpapi, &$package) {
        $output  = [];
        $output['package'] = $package->name();
        $classes = $package->allClasses();

        if ($classes) {
            ksort($classes);
            foreach ($classes as $name => $class) {
                $output['class'][$name]['path']    = str_repeat('../', $this->depth).$class->asPath();
                $output['class'][$name]['name']    = $class->name();
                $output['class'][$name]['package'] = $class->packageName();
            }
        }

        $interfaces = $package->interfaces();
        if ($interfaces && is_array($interfaces)) {
            ksort($interfaces);
            foreach ($interfaces as $i => $interface) {
                $output['interface'][$i]['path']    = str_repeat('../', $this->depth).$interface->asPath();
                $output['interface'][$i]['name']    = $interface->name();
                $output['interface'][$i]['package'] = $interface->packageName();
            }
        }

        $traits = $package->traits();
        if ($traits && is_array($traits)) {
            ksort($traits);
            foreach ($traits as $i => $trait) {
                $output['trait'][$i]['path']    = str_repeat('../', $this->depth).$trait->asPath();
                $output['trait'][$i]['name']    = $trait->name();
                $output['trait'][$i]['package'] = $trait->packageName();
            }
        }

        $exceptions = $package->exceptions();
        if ($exceptions && is_array($exceptions)) {
            ksort($exceptions);
            foreach ($exceptions as $i => $exception) {
                $output['exception'][$i]['path']    = str_repeat('../', $this->depth).$exception->asPath();
                $output['exception'][$i]['name']    = $exception->name();
                $output['exception'][$i]['package'] = $exception->packageName();
            }
        }

        $functions = $package->functions();
        if ($functions) {
            ksort($functions);
            foreach ($functions as $name => $function) {
                $output['function'][$name]['path']    = str_repeat('../', $this->depth).$function->asPath();
                $output['function'][$name]['name']    = $function->name();
                $output['function'][$name]['package'] = $function->packageName();
            }
        }

        $globals = $package->globals();
        if ($globals) {
            ksort($globals);
            foreach ($globals as $name => $global) {
                $output['global'][$name]['path']    = str_repeat('../', $this->depth).$global->asPath();
                $output['global'][$name]['name']    = $global->name();
                $output['global'][$name]['package'] = $global->packageName();
            }
        }

        $tpl = new template($phpapi->options['doclet'], 'package-items.tpl');
        return $tpl->parse($output);
    }
}
