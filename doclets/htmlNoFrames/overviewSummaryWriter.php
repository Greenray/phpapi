<?php
/** This class generates the list of all parsed packages.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlNoFrames/overviewSummaryWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlNoFrames
 */

class overviewSummaryWriter extends htmlWriter {

    /** Build the package index.
     *
     * @param Doclet doclet
     */
    public function overviewSummaryWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $phpapi = &$this->doclet->phpapi();

        $this->sections[0] = ['title' => 'Overview', 'selected' => TRUE];
        $this->sections[1] = ['title' => 'Namespace'];
        $this->sections[2] = ['title' => 'Class'];
        $this->sections[3] = ['title' => 'Tree',       'url' => 'tree.html'];
        $this->sections[4] = ['title' => 'Deprecated', 'url' => 'deprecated.html'];
        $this->sections[5] = ['title' => 'Todo',       'url' => 'todo.html'];
        $this->sections[6] = ['title' => 'Index',      'url' => 'index-all.html'];

        $output = [];
        $output['title'] = $phpapi->options['docTitle'];

        $rootDoc  = &$this->doclet->rootDoc();
        $overview = &$rootDoc->tags('@text');
        $output['description']  = $this->processInlineTags($overview, TRUE);
        $output['overviewFile'] = basename($phpapi->options['overview']);

        $packages = &$rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $description = &$package->tags('@text');
            $output['package'][$name]['path'] = $package->asPath().DS;
            $output['package'][$name]['name'] = $package->name();
            $output['package'][$name]['desc'] = strip_tags($this->processInlineTags($description, TRUE), '<a><b><strong><u><em>');
        }
        $output['overview'] = $this->processInlineTags($overview);

        $tpl = new template($phpapi->options['doclet'], 'index.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('index.html', 'Overview');
    }
}
