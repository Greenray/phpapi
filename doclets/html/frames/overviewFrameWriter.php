<?php
/** This generates the overview frame used for displaying the list of package links
 * in the upper-left frame in the frame-formatted default output.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/frames/overviewFrameWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   frames
 */

class overviewFrameWriter extends htmlWriter {

    /** Build the frame of packages index.
     * @param object &$doclet The reference to the documentation generator
     */
    public function __construct(&$doclet) {
        parent::htmlWriter($doclet);

        $output['header'] = $doclet->header;
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $output['package'][$name]['path'] = $package->asPath().DS;
            $output['package'][$name]['name'] = $package->name;
        }

        $tpl = new template($doclet->rootDoc->phpapi->options['doclet'], 'overview-frame.tpl');
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('overview-frame.html', 'Overview', FALSE);
    }
}
