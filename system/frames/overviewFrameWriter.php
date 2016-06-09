<?php
/**
 * This generates the overview frame used for displaying the list of package links
 * in the upper-left frame in the frame-formatted default output.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      /system/frames/overviewFrameWriter.php
 * @package   frames
 */

class overviewFrameWriter extends htmlWriter {

    /**
     * Builds the frame of packages index.
     *
     * @param doclet &$doclet Reference to the documentation generator
     */
    public function __construct(&$doclet) {
        parent::htmlWriter($doclet);

        $output   = [];
        $packages = &$doclet->rootDoc->packages;
        ksort($packages);
        foreach ($packages as $name => $package) {
            $output[$name]['path'] = $package->path().DS;
            $output[$name]['name'] = $package->name;
        }
        $tpl = new template();
        $tpl->set('header',   $doclet->header);
        $tpl->set('packages', $output);
        $this->output = $tpl->parse($doclet->rootDoc->phpapi, 'overview-frame');
        $this->write('overview-frame.html', 'Overview', FALSE);
    }
}
