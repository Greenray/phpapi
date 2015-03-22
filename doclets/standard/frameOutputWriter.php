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

        $phpapi =& $this->_doclet->phpapi();
        $tpl    = new template($phpapi->getOption('doclet'), 'frame-output');

        ob_start();

        echo $tpl->parse();

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('index.html', FALSE, FALSE);

        # Builds the header frame
        $tpl = new template($phpapi->getOption('doclet'), 'header');
        $output['title'] = $this->_doclet->docTitle();

        ob_start();

        echo $tpl->parse($output);

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('header.html', 'Header', FALSE);

        # Builds the footer frame
        $tpl = new template($phpapi->getOption('doclet'), 'footer');
        ob_start();

        echo $tpl->parse();

        $this->_output = ob_get_contents();
        ob_end_clean();

        $this->_write('footer.html', 'Footer', FALSE);
    }
}
