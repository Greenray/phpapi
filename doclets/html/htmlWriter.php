<?php
/** Generates menu and writes html pages.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/globalWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 * @overview  HTML documentation generator.
 */

class htmlWriter {

    /** @var integer The directory structure depth. Used to calculate relative paths */
    public $depth = 0;

    /** @var doclet The doclet that created this object */
    public $doclet;

    /** @var string The <body> id attribute value, used for selecting style */
    public $id = 'overview';

    /** @var string List of package, functions, globals... */
    public $items = '';

    /** @var string The output body */
    public $output = '';

    /** @var array The section titles to place in the header */
    public $sections = [];

    /** Constructor.
     * @param object &$doclet The reference to documentation generator
     */
    public function htmlWriter(&$doclet) {
        $this->doclet = &$doclet;
    }

    /** Builds the navigation bar.
     * @param  string $path The path to write the file to
     * @param  string $file Filename for which navigation to create (default = '')
     * @return string       Navigation for documentation
     */
    public function nav($path, $file = '') {
        $output = [];
        $output['header'] = $this->doclet->header;
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

        $output['class']    = ($thisClass === 'classWriter')    ? TRUE : FALSE;
        $output['function'] = ($thisClass === 'functionWriter') ? TRUE : FALSE;
        $output['global']   = ($thisClass === 'globalWriter')   ? TRUE : FALSE;

        $tpl = new template($this->doclet->rootDoc->phpapi->options['doclet'], 'navigation.tpl');
        return $tpl->parse($output);
    }

    /** Builds all items of the class.
     * @param  phpapi     &$phpapi  The reference to the application object
     * @param  packageDoc &$package The reference to the current package
     * @return string               Parsed template "class-items"
     */
    public function packageItems(&$phpapi, &$package) {
        $output  = [];

        $packages = &$this->doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $pack) {
            $output['package'][$name]['path'] = str_repeat('../', $this->depth).$pack->asPath().DS;
            $output['package'][$name]['name'] = $pack->name;
        }

        $output['current'] = $package->name;
        $classes = $package->classes;

        if ($classes) {
            ksort($classes);
            foreach ($classes as $i => $class) {
                $output['class'][$i]['path']    = str_repeat('../', $this->depth).$class->asPath();
                $output['class'][$i]['name']    = $class->name;
                $output['class'][$i]['package'] = $class->package;
            }
        }

        $interfaces = $package->interfaces();
        if ($interfaces) {
            ksort($interfaces);
            foreach ($interfaces as $i => $interface) {
                $output['interface'][$i]['path']    = str_repeat('../', $this->depth).$interface->asPath();
                $output['interface'][$i]['name']    = $interface->name;
                $output['interface'][$i]['package'] = $interface->package;
            }
        }

        $traits = $package->traits();
        if ($traits) {
            ksort($traits);
            foreach ($traits as $i => $trait) {
                $output['trait'][$i]['path']    = str_repeat('../', $this->depth).$trait->asPath();
                $output['trait'][$i]['name']    = $trait->name;
                $output['trait'][$i]['package'] = $trait->package;
            }
        }

        $exceptions = $package->exceptions();
        if ($exceptions) {
            ksort($exceptions);
            foreach ($exceptions as $i => $exception) {
                $output['exception'][$i]['path']    = str_repeat('../', $this->depth).$exception->asPath();
                $output['exception'][$i]['name']    = $exception->name;
                $output['exception'][$i]['package'] = $exception->package;
            }
        }

        $functions = $package->functions;
        if ($functions) {
            ksort($functions);
            foreach ($functions as $i => $function) {
                $output['function'][$i]['path']    = str_repeat('../', $this->depth).$function->asPath();
                $output['function'][$i]['name']    = $function->name;
                $output['function'][$i]['package'] = $function->package;
            }
        }

        $globals = $package->globals;
        if ($globals) {
            ksort($globals);
            foreach ($globals as $i => $global) {
                $output['global'][$i]['path']    = str_repeat('../', $this->depth).$global->asPath();
                $output['global'][$i]['name']    = $global->name;
                $output['global'][$i]['package'] = $global->package;
            }
        }

        $tpl = new template($phpapi->options['doclet'], 'package-items.tpl');
        return $tpl->parse($output);
    }

    /** Converts inline tags into a string for outputting.
     * @param  tag     &$tag  Object reference
     * @param  boolean $first Process first line of comment only
     * @return string         The string representation of the elements doc tags
     */
    protected function processInlineTags(&$tag, $first = FALSE) {
        $description = '';
        if (is_array($tag)) $tag = $tag[0];
        if (is_object($tag)) {
            if ($first)
                 $tags = &$tag->firstCommentString();
            else $tags = &$tag->inlineTags();
            if ($tags) {
                foreach ($tags as $aTag) {
                    if ($aTag) $description .= $aTag->text;
                }
            }
            return $this->doclet->formatter->toPlainText($description);
        }
        if (is_string($tag)) return $tag;
        return '';
    }

    /** Formats tags for output.
     * @param  tag    &$tag Object reference
     * @param  object $obj  The currently processed object
     * @return string       The text representation of the tags
     */
    protected function processTags(&$tags, $obj = NULL) {
        $output  = '';
        foreach ($tags as $key => $tag) {
            if ($key !=='@text') {
                if (is_array($tag)) {
                    $hasText = FALSE;
                    foreach ($tag as $i => $tagFromGroup) {
                        if ($tagFromGroup->text !== '') $hasText = TRUE;
                    }
                    if ($hasText) {
                        $usedTag = '';
                        foreach ($tag as $k => $tagFromGroup) {
                            $param = explode('+', $tagFromGroup->text);

                            if ($tag[0]->displayName() !== $usedTag)
                                 $output['tag'][$k]['name'] = $tag[0]->displayName();
                            else $output['tag'][$k]['name'] = '&nbsp';

                            $output['tag'][$k]['type'] = $tagFromGroup->type;
                            if ($obj) {
                                if (!empty($obj->parameters[$param[0]]->type->typeName)) {
                                    if (class_exists($obj->parameters[$param[0]]->type->typeName)) {
                                        $classDoc = &$obj->parameters[$param[0]]->type->asClassDoc();
                                        if ($classDoc) {
                                            $output['tag'][$k]['type'] = '<a href="'.str_repeat('../', $this->depth).$classDoc->asPath().'">'.$tagFromGroup->type.'</a>';
                                        }
                                    }
                                }
                            }
                            if (!empty($param[1])) {
                                $output['tag'][$k]['var']     = $param[0];
                                $output['tag'][$k]['comment'] = preg_replace("#[\'\"](.*?)[\'\"]#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($param[1])));
                            } else {
                                $output['tag'][$k]['var']     = '';
                                $output['tag'][$k]['comment'] = preg_replace("#[\'\"](.*?)[\'\"]#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($param[0])));
                            }
                            $usedTag = $tag[0]->displayName();
                        }
                    }
                } else {
                    $text = $tag->text;
                    if ($text !== '') {
                        $param = explode('+', $text);
                        $output['tag'][$key]['name'] = $tag->displayName();
                        if (!empty($tag->type)) {
                            $output['tag'][$key]['type'] = $tag->type;
                            if ($obj) {
                                if (!empty($obj->parameters[$param[0]]->type->typeName)) {
                                    if (class_exists($obj->parameters[$param[0]]->type->typeName)) {
                                        $classDoc = &$obj->parameters[$param[0]]->type->asClassDoc();
                                        if ($classDoc) {
                                            $output['tag'][$key]['type'] = '<a href="'.str_repeat('../', $this->depth).$classDoc->asPath().'">'.$tag->type.'</a>';
                                        }
                                    }
                                }
                            }
                        } else $output['tag'][$key]['type'] = '&nbsp;';

                        if (!empty($param[1])) {
                             $output['tag'][$key]['var']     = $param[0];
                             $output['tag'][$key]['comment'] = preg_replace("#[\'\"](.*?)[\'\"]#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($param[1])));
                        } else {
                             $output['tag'][$key]['var']     = '';
                             $output['tag'][$key]['comment'] = preg_replace("#[\'\"](.*?)[\'\"]#is", '<span class="red">\'\\1\'</span>', htmlspecialchars(trim($param[0])));
                        }
                    }
                }
            }
        }
        if (!empty($output)) {
            $tpl    = new template($this->doclet->rootDoc->phpapi->options['doclet'], 'tags.tpl');
            $output = $tpl->parse($output);
        }
        return $output;
    }

    /** Preparation of the object for html template.
     * @param  object $object Object (fields, methods, constants, variables...)
     * @return array          The result
     */
    public function showObject($object) {
        $regexp = ["#[\'\"](.*?)[\'\"]#is" => '<span class="red">\'\\1\'</span>',
                   "#<a(.*)>(.*?)</a>#is"  => '\\2'];
        $output  = [];
        foreach ($object as $key => $element) {
            $output[$key]['id']        = trim($element->name, '&$');
            $output[$key]['name']      = $element->name;
            $output[$key]['modifiers'] = $element->modifiers();

            if     (method_exists($element, 'typeAsString'))       $output[$key]['type'] = $element->typeAsString();
            elseif (method_exists($element, 'returnTypeAsString')) $output[$key]['type'] = $element->returnTypeAsString();

            if (method_exists($element, 'signature')) $output[$key]['signature'] = $element->signature();
            if (isset($element->value) && !is_null($element->value)) {
                   $value = $element->value;
                   if ((strlen($value) > 100) && (substr($value, 0, 5) === 'array') || (substr($value, 0, 1) === '[') && (substr($value, -1, 1) === ']')) {
                       $value  = str_replace(["\r\n", "\n\r", "\r", "\n"], '<br />', $value);
                   }
                   $output[$key]['value'] = ' = '.preg_replace(array_keys($regexp), array_values($regexp), $value);
            } else $output[$key]['value'] = '';

            $text = (isset($element->tags['@text'])) ? $element->tags['@text'] : __('Описания нет');
            $output[$key]['shortDesc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
            $output[$key]['fullDesc']  = $this->processInlineTags($text);
            $output[$key]['tags']      = $this->processTags($element->tags, $element);
            if (method_exists($element, 'location')) $output[$key]['location'] = $element->location();
        }
        return $output;
    }

    /** Writes the HTML page to disk using the given path.
     * @param string  $file  The path to write the file to
     * @param string  $title The title for new page
     * @param boolean $menu  To place the menu on the page
     */
    public function write($file, $title, $menu = TRUE) {
        $phpapi = &$this->doclet->rootDoc->phpapi;

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

            if ($title)
                 $output['docTitle'] = $title.' ('.$phpapi->options['docTitle'].')';
            else $output['docTitle'] = $phpapi->options['docTitle'];
            $output['header'] = $phpapi->options['docTitle'];

            if ($menu) {
                $output['menu'] = $this->nav($output['path'], $file);
            }

            if (!empty($this->items)) {
                $output['items'] = $this->items;
                unset($this->items);
            } else {
                $output['items'] = ($phpapi->options['doclet'] === 'plain') ? items::items($phpapi, $this->doclet, $output['path']) : NULL;
            }

            $output['page'] = $this->output;

            $tpl = new template($phpapi->options['doclet'], 'main.tpl');
            fwrite($fp, $tpl->parse($output));
            fclose($fp);

        } else {
            $phpapi->error('Cannot write "'.$this->doclet->destination.$file.'"');
            exit;
        }
    }
}
