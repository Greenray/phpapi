<?php
# phpapi: The PHP Documentation Creator

/** This generates the index.html file used for presenting the frame-formated
 * "cover page" of the API documentation.
 * @file      doclets/standard/farmeOutputWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Standard
 */

class frameOutputWriter extends HTMLWriter {

    /** Build the HTML frameset.
     * @param Doclet doclet
     */
    public function frameOutputWriter(&$doclet) {
        parent::HTMLWriter($doclet);

        ob_start();
        echo <<<END
<frameset rows="7%,88%,5%" framespacing="0">
    <frameset>
        <frame src="header-frame.html" name="header" noresize scrolling="no">
    </frameset>
    <frameset cols="250,*" framespacing="0">
        <frameset rows="30%,70%" framespacing="0">
            <frame src="overview-frame.html" name="packagelist">
            <frame src="allitems-frame.html" name="index">
        </frameset>
        <frame src="overview-summary.html" name="main">
    </frameset>
    <frame src="footer-frame.html" name="footer" noresize scrolling="no">
</frameset>
<noframes>
        <body>
            <h2>Frame Alert</h2>
            <p>This document is designed to be viewed using frames. If you see this message, you are using a non-frame-capable browser.<br>
            Link to <a href="overview-summary.html">Non-frame version</a>.</p>
        </body>
    </noframes>
END;
        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('index.html', FALSE, FALSE);
    }

    /** Get the HTML DOCTYPE for this output.
     * @return string
     */
    public function _doctype() {
        return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
    }
}
