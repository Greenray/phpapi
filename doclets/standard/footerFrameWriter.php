<?php
# phpapi: The PHP Documentation Creator

/** This generates the footer-frame.html file.
 * of the generated documentation.
 *
 * @file      doclets/standard/footerFrameWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class footerFrameWriter extends htmlWriter {

    /** Build the header frame index.
     * @param Doclet doclet
     */
    public function footerFrameWriter(&$doclet) {
        parent::htmlWriter($doclet);

        ob_start();

        echo '<div id="footer"'.GENERATOR.' '.COPYRIGHT.'</div>';

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('footer-frame.html', 'Footer', FALSE);
    }
}
