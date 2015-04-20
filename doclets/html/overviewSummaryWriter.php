<?php
/**
 * This class generates the list of all parsed packages.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      doclets/html/overviewSummaryWriter.php
 * @version   4.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   html
 */

class overviewSummaryWriter extends htmlWriter {

    /**
     * Builds the package index.
     *
     * @param object &$doclet Reference to the documentation generator
     */
    public function __construct(&$doclet, $index) {
        parent::htmlWriter($doclet);

        $this->sections[0] = ['title' => 'Overview', 'selected' => TRUE];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $output['title'] = $doclet->rootDoc->phpapi->options['docTitle'];

        $overview = (isset($doclet->rootDoc->tags['@text'])) ? $doclet->rootDoc->tags['@text'] : __('Описания нет');
        $output['description']  = $this->processInlineTags($overview, TRUE);
        $output['overview']     = $this->processInlineTags($overview);
        $output['overviewFile'] = basename($doclet->rootDoc->phpapi->options['overview']);

        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $text = (isset($package->desc)) ? $package->desc : __('Описания нет');
            $output['package'][$name]['path'] = $package->asPath().DS;
            $output['package'][$name]['name'] = $package->name;
            $output['package'][$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
        }

        $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], $index.'.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write($index.'.html', 'Overview');
    }
}
