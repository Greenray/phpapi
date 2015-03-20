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

        ob_start();

        echo '<body id="frame">';
        echo '<h1>'.$this->_doclet->getHeader().'</h1>';
        echo '<ul>';
        echo '<li><a href="allitems.html" target="index">All Items</a></li>';
        echo '</ul>';
        echo '<h1>Namespaces</h1>';
        $rootDoc =& $this->_doclet->rootDoc();
        echo '<ul>';
        $packages =& $rootDoc->packages();
        ksort($packages);
        foreach ($packages as $name => $package) {
            echo '<li><a href="'.$package->asPath().DS.'package-frame.html" target="index">'.$package->name().'</a></li>';
        }
        echo '</ul>';
        echo '</body>';

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('overview-frame.html', 'Overview', FALSE);
    }
}