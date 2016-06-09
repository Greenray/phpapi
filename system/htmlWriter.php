<?php
/**
 * Generates menu and writes html pages.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/globalWriter.php
 * @package   html
 * @overview  HTML documentation generator.
 */

class htmlWriter {

    /** @var integer Directory structure depth. Used to calculate relative paths */
    public $depth = 0;

    /** @var doclet Doclet that created this object */
    public $doclet;

    /** @var string The <body> id attribute value, used for selecting style */
    public $id = 'overview';

    /** @var string List of package, functions, globals... */
    public $items = '';

    /** @var string Output body */
    public $output = '';

    private $regexp = [
        "#[\"](.*?)[\"]#is"     => '<span class="red">"\\1"</span>',
        "#&quot;(.*?)&quot;#is" => '<span class="red">"\\1"</span>',
        "#[\'](.*?)[\']#is"     => '<span class="red">"\\1"</span>',
        "#<a(.*)>(.*?)</a>#is"  => '\\2'
    ];

    /** @var array Section titles to place in the header */
    public $sections = [];

    /**
     * Constructor.
     *
     * @param doclet &$doclet Reference to documentation generator
     */
    public function htmlWriter(&$doclet) {
        $this->doclet = &$doclet;
    }

    /**
     * Builds the navigation bar.
     *
     * @param  string $path Path to write the file to
     * @param  string $file Filename for which navigation to create (default = '')
     * @return string       Navigation for documentation
     */
    public function nav($path, $file = '') {
        $tpl = new template();

        $tpl->set('header', $this->doclet->header);
        $tpl->set('path',   $path);
        $tpl->set('file',   $file);

        $output = [];
        foreach ($this->sections as $key => $section) {
            $output[$key]['title'] = $section['title'];
            if (isset($section['selected']) && $section['selected']) {
                $output[$key]['selected'] = $section['selected'];
            } else {
                if (isset($section['url']))
                     $output[$key]['title'] = '<a href="'.$path.$section['url'].'">'.$section['title'].'</a>';
                else $output[$key]['title'] = $section['title'];
            }
        }
        $tpl->set('sections', $output);

        $thisClass = get_class($this);

        $tpl->set('class',    ($thisClass === 'classWriter')    ? TRUE : FALSE);
        $tpl->set('function', ($thisClass === 'functionWriter') ? TRUE : FALSE);
        $tpl->set('global',   ($thisClass === 'globalWriter')   ? TRUE : FALSE);

        return $tpl->parse($this->doclet->rootDoc->phpapi, 'navigation');
    }

    /**
     * Builds all items of the class.
     *
     * @param  phpapi     &$phpapi  Reference to the application object
     * @param  packageDoc &$package Reference to the current package
     * @return string               Parsed template "class-items"
     */
    public function packageItems(&$phpapi, &$package) {
        $output   = [];
        $path     = str_repeat('../', $this->depth);
        $tpl      = new template();
        $packages = &$this->doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $pack) {
            $output[$name]['path'] = $path.$pack->path().DS;
            $output[$name]['name'] = $pack->name;
        }
        $tpl->set('packages', $output);
        $tpl->set('current',  $package->name);

        $classes = $package->classes;
        if ($classes) {
            ksort($classes);
            $output = [];
            foreach ($classes as $i => $class) {
                $output[$i]['path']    = $path.$class->path();
                $output[$i]['name']    = $class->name;
                $output[$i]['package'] = $class->package;
            }
            $tpl->set('classes', $output);
        }

        $interfaces = $package->interfaces();
        if ($interfaces) {
            ksort($interfaces);
            $output = [];
            foreach ($interfaces as $i => $interface) {
                $output[$i]['path']    = $path.$interface->path();
                $output[$i]['name']    = $interface->name;
                $output[$i]['package'] = $interface->package;
            }
            $tpl->set('interfaces', $output);
        }

        $traits = $package->traits();
        if ($traits) {
            ksort($traits);
            $output = [];
            foreach ($traits as $i => $trait) {
                $output[$i]['path']    = $path.$trait->path();
                $output[$i]['name']    = $trait->name;
                $output[$i]['package'] = $trait->package;
            }
            $tpl->set('traits', $output);
        }

        $exceptions = $package->exceptions();
        if ($exceptions) {
            ksort($exceptions);
            $output = [];
            foreach ($exceptions as $i => $exception) {
                $output[$i]['path']    = $path.$exception->path();
                $output[$i]['name']    = $exception->name;
                $output[$i]['package'] = $exception->package;
            }
            $tpl->set('exceptions', $output);
        }

        $functions = $package->functions;
        if ($functions) {
            ksort($functions);
            $output = [];
            foreach ($functions as $i => $function) {
                $output[$i]['path']    = $path.$function->path();
                $output[$i]['name']    = $function->name;
                $output[$i]['package'] = $function->package;
            }
            $tpl->set('functions', $output);
        }

        $globals = $package->globals;
        if ($globals) {
            ksort($globals);
            $output = [];
            foreach ($globals as $i => $global) {
                $output[$i]['path']    = $path.$global->path();
                $output[$i]['name']    = $global->name;
                $output[$i]['package'] = $global->package;
            }
            $tpl->set('globals', $output);
        }

        return $tpl->parse($phpapi, 'package-items');
    }

    /**
     * Converts inline tags into a string for outputting.
     *
     * @param  tag     &$tag  Object reference
     * @param  boolean $first Process first line of comment only
     * @return string         String representation of the elements doc tags
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

    /**
     * Formats parameters for output.
     * Returns the list of parameters with their types.
     * Recognised types are turned into HTML anchor tags to the documentation page for the class defining them.
     *
     * @param  tag    &$tag Object reference
     * @param  object $obj  Currently processed object
     * @return array        Text representation of the parameters
     */
    protected function parameters(&$tags, $obj = NULL) {
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
                                 $output[$k]['name'] = $tag[0]->displayName();
                            else $output[$k]['name'] = '&nbsp';

                            $output[$k]['type'] = $tagFromGroup->type;
                            if ($obj) {
                                if (!empty($obj->parameters[$param[0]]->type->typeName)) {
                                    if (class_exists($obj->parameters[$param[0]]->type->typeName)) {
                                        $classDoc = &$obj->parameters[$param[0]]->type->isClass();
                                        if ($classDoc) {
                                            $output[$k]['type'] = '<a href="'.str_repeat('../', $this->depth).$classDoc->path().'">'.$tagFromGroup->type.'</a>';
                                        }
                                    }
                                }
                            }
                            if (!empty($param[1])) {
                                $output[$k]['var']     = $param[0];
                                $output[$k]['comment'] = preg_replace(array_keys($this->regexp), array_values($this->regexp), htmlspecialchars(trim($param[1])));
                            } else {
                                $output[$k]['comment'] = preg_replace(array_keys($this->regexp), array_values($this->regexp), htmlspecialchars(trim($param[0])));
                            }
                            $usedTag = $tag[0]->displayName();
                        }
                    }
                } else {
                    $text = $tag->text;
                    if ($text !== '') {
                        $param = explode('+', $text);
                        $output[$key]['name'] = $tag->displayName();
                        if (!empty($tag->type)) {
                            $output[$key]['type'] = $tag->type;
                            if ($obj) {
                                if (!empty($obj->parameters[$param[0]]->type->typeName)) {
                                    if (class_exists($obj->parameters[$param[0]]->type->typeName)) {
                                        $classDoc = &$obj->parameters[$param[0]]->type->isClass();
                                        if ($classDoc) {
                                            $output[$key]['type'] = '<a href="'.str_repeat('../', $this->depth).$classDoc->path().'">'.$tag->type.'</a>';
                                        }
                                    }
                                }
                            }
                        } else $output[$key]['type'] = '&nbsp;';

                        if (!empty($param[1])) {
                             $output[$key]['var']     = $param[0];
                             $output[$key]['comment'] = preg_replace(array_keys($this->regexp), array_values($this->regexp), htmlspecialchars(trim($param[1])));
                        } else {
                             $output[$key]['comment'] = preg_replace(array_keys($this->regexp), array_values($this->regexp), htmlspecialchars(trim($param[0])));
                        }
                    }
                }
            }
        }
        if (!empty($output)) {
            $tpl = new template();
            $tpl->set('tags', $output);
            $output = $tpl->parse($this->doclet->rootDoc->phpapi, 'tags');
        }
        return $output;
    }

    /**
     * Preparation of the object for html template.
     *
     * @param  object $object Object (fields, methods, constants, variables...)
     * @return array          Result
     */
    public function showObject($object) {
        $output = [];
        foreach ($object as $key => $element) {
            $output[$key]['id']        = trim($element->name, '&$');
            $output[$key]['name']      = $element->name;
            $output[$key]['modifiers'] = $element->modifiers();

            if     (method_exists($element, 'type'))       $output[$key]['type'] = $element->type();
            elseif (method_exists($element, 'returnType')) $output[$key]['type'] = $element->returnType();

            if (method_exists($element, 'arguments')) $output[$key]['arguments'] = $element->arguments();
            if (isset($element->value) && !is_NULL($element->value)) {
                   $value = $element->value;
                   if ((strlen($value) > 100) && (substr($value, 0, 5) === 'array') || (substr($value, 0, 1) === '[') && (substr($value, -1, 1) === ']')) {
                       $value  = str_replace(["\r\n", "\n\r", "\r", "\n"], '<br />', $value);
                   }
                   $output[$key]['value'] = ' = '.preg_replace(array_keys($this->regexp), array_values($this->regexp), $value);
            } else $output[$key]['value'] = '';

            $text = (isset($element->tags['@text'])) ? $element->tags['@text'] : __('No description');
            $output[$key]['shortDesc']  = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
            $output[$key]['fullDesc']   = $this->processInlineTags($text);
            $output[$key]['parameters'] = $this->parameters($element->tags, $element);
            if (method_exists($element, 'location')) $output[$key]['location'] = $element->location();
            if (!empty($element->includes)) {
                $output[$key]['includes'] = '';
                foreach($element->includes as $i => $file) {
                    $output[$key]['includes'] .= substr(preg_replace(array_keys($this->regexp), array_values($this->regexp), $file), 0, -1).'<br />';
                }
            }
        }
        return $output;
    }

    /**
     * Writes the HTML page to disk using the given path.
     *
     * @param string  $file  Path to write the file to
     * @param string  $title Title for new page
     * @param boolean $menu  To place the menu on the page
     */
    public function write($file, $title, $menu = TRUE) {
        $phpapi = &$this->doclet->rootDoc->phpapi;
        #
        # Make directories if they don't exist
        #
        $dirs = explode(DS, $file);
        array_pop($dirs);
        $destPath = $phpapi->options['destination'];
        foreach ($dirs as $dir) {
            $destPath .= $dir.DS;
            if (!is_dir($destPath)) {
                if (!@mkdir($destPath)) {
                    $phpapi->error('Cannot create directory '.$destPath);
                    exit;
                }
            }
        }

        $path = !empty($dirs) ? str_repeat('../', $this->depth) : './';
        $tpl  = new template();
        $tpl->set('id',       $this->id);
        $tpl->set('path',     $path);
        $tpl->set('header',   $phpapi->options['docTitle']);
        $tpl->set('docTitle', $phpapi->options['docTitle']);

        if ($title) $tpl->set('docTitle', $title.' ('.$phpapi->options['docTitle'].')');
        if ($menu)  $tpl->set('menu', $this->nav($path, $file));

        if (!empty($this->items)) {
            $tpl->set('items', $this->items);
            unset($this->items);

        } else $tpl->set('items', ($phpapi->options['doclet'] === 'plain') ? items::items($phpapi, $this->doclet, $path) : NULL);

        $tpl->set('page', $this->output);
        #
        # Write file
        #
        $fp = fopen($phpapi->options['destination'].$file, 'w');
        if ($fp) {
            $phpapi->verbose('Writing '.$file);
            fwrite($fp, $tpl->parse($phpapi, 'main'));
            fclose($fp);

        } else {
            $phpapi->error('Cannot write '.$phpapi->options['destination'].$file);
            exit;
        }
    }
}
