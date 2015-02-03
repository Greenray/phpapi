<?php
# phpapi: The PHP Documentation Creator

/** This generates the header-frame.html file used for displaying the main header
 * of the generated documentation.
 * @file      doclets/standard/headerFrameWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class headerFrameWriter extends HTMLWriter {

    /** Build the header frame index.
     * @param Doclet doclet
     */
    public function headerFrameWriter(&$doclet) {
        parent::HTMLWriter($doclet);

        ob_start();

        echo '<body>';
        echo '<div id="header"><h1>Content Management System idxCMS</h1></div>';
        echo '</body>';
        $this->_output = ob_get_contents();

        ob_end_clean();

        $this->_write('header-frame.html', 'Header', FALSE);
    }
}
