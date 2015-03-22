<?php
# phpapi: The PHP Documentation Creator

/** This generates the package-frame.html file used for displaying the list
 * of package links in the upper-left frame in the frame-formatted default output.
 *
 * @file      doclets/standard/overviewFrameWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class overviewFrameWriter extends htmlWriter {

    /** Build the package frame index.
     * @param Doclet doclet
     */
    public function overviewFrameWriter(&$doclet) {
        parent::htmlWriter($doclet);
        $phpapi =& $this->_doclet->phpapi();
        $output = [];
        $output['header'] = $this->_doclet->getHeader();
        $rootDoc =& $this->_doclet->rootDoc();
        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $name => $package) {
            $output['package'][$name]['path'] = $package->asPath().DS;
            $output['package'][$name]['name'] = $package->name();
        }

        $tpl = new template($phpapi->getOption('doclet'), 'overview-frame');
        ob_start();

        echo $tpl->parse($output);

        $this->_output = ob_get_contents();
        ob_end_clean();
        $this->_write('overview-frame.html', 'Overview', FALSE);
    }
}
