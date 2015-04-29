<?php
/**
 * This class generates the list of all parsed packages.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      doclets/html/overviewSummaryWriter.php
 * @package   html
 */

class overviewSummaryWriter extends htmlWriter {

    /**
     * Builds the package index.
     *
     * @param doclet &$doclet Reference to the documentation generator
     */
    public function __construct(&$doclet, $page) {
        parent::htmlWriter($doclet);

        $rootDoc = &$doclet->rootDoc;

        $this->sections[0] = ['title' => 'Overview', 'selected' => TRUE];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $overview = (isset($rootDoc->tags['@text'])) ? $rootDoc->tags['@text'] : __('No description');

        $tpl = new template();
        $tpl->set('title',        $rootDoc->phpapi->options['docTitle']);
        $tpl->set('description',  $this->processInlineTags($overview, TRUE));
        $tpl->set('overview',     $this->processInlineTags($overview));
        $tpl->set('overviewFile', basename($rootDoc->phpapi->options['overview']));

        $output   = [];
        $packages = &$rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $text = (isset($package->desc)) ? $package->desc : __('No description');
            $output[$name]['path'] = $package->asPath().DS;
            $output[$name]['name'] = $package->name;
            $output[$name]['desc'] = strip_tags($this->processInlineTags($text, TRUE), '<a><b><strong><u><em>');
        }
        $tpl->set('packages', $output);
        $this->output = $tpl->parse($rootDoc->phpapi, $page);
        $this->write($page.'.html', 'Overview');
    }
}
