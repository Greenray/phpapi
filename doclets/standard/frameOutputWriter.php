<?php
# phpapi: The PHP Documentation Creator

/** This generates the file used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @file      doclets/standard/frameOutputWriter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class frameOutputWriter extends htmlWriter {

    /** Builds the HTML frameset.
     * @param Doclet doclet
     */
    public function frameOutputWriter(&$doclet) {
        parent::htmlWriter($doclet);

        ob_start();

        echo
'<frameset rows="7%,88%,5%" framespacing="0">
    <frameset>
        <frame src="header.html" name="header" noresize scrolling="no">
    </frameset>
    <frameset cols="250,*" framespacing="0">
        <frameset rows="30%,70%" framespacing="0">
            <frame src="overview-frame.html" name="packagelist">
            <frame src="allitems.html" name="index">
        </frameset>
        <frame src="overview-summary.html" name="main">
    </frameset>
    <frameset>
        <frame src="footer.html" name="footer" noresize scrolling="no">
    </frameset>
</frameset>
<noframes>
    <body>
        <h2>Frame Alert</h2>
        <p>This document is designed to be viewed using frames. If you see this message, you are using a non-frame-capable browser.<br>
        Link to <a href="overview-summary.html">Non-frame version</a>.</p>
    </body>
</noframes>';

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('index.html', FALSE, FALSE);

        # Builds the header frame
        ob_start();

        echo '<body>';
        echo '<div id="header"><h1>'.$this->_doclet->docTitle().'</h1></div>';
        echo '</body>';

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('header.html', 'Header', FALSE);

        # Builds the footer frame
        ob_start();

        echo '<div id="footer">'.GENERATOR.' '.COPYRIGHT.'</div>';

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('footer.html', 'Footer', FALSE);
    }
}
