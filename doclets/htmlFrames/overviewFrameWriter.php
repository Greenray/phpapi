<?php
/** This generates the package-frame.html file used for displaying the list
 * of package links in the upper-left frame in the frame-formatted default output.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlFrames/overviewFrameWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlFrames
 */

class overviewFrameWriter extends htmlWriter {

    /** Build the package frame index.
     * @param Doclet doclet
     */
    public function overviewFrameWriter(&$doclet) {
        parent::htmlWriter($doclet);
        $phpapi = &$this->doclet->phpapi();
        $output = [];
        $output['header'] = $this->doclet->getHeader();
        $rootDoc = &$this->doclet->rootDoc();
        $packages = &$rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $output['package'][$name]['path'] = $package->asPath().DS;
            $output['package'][$name]['name'] = $package->name();
        }

        $tpl = new template($phpapi->options['doclet'], 'overview-frame.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('overview-frame.html', 'Overview', FALSE);
    }
}
