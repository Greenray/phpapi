<?php
/** This generates the file used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlFrames/frameOutputWriter.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlFrames
 */

class frameOutputWriter extends htmlWriter {

    /** Builds the HTML frameset.
     *
     * @param Doclet doclet
     */
    public function frameOutputWriter(&$doclet) {
        parent::htmlWriter($doclet);

        $phpapi = &$this->doclet->phpapi();
        $tpl    = new template($phpapi->options['doclet'], 'frame-output.tpl');
        ob_start();

        echo $tpl->parse();

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('index.html', FALSE, FALSE);

        # Builds the header frame
        $tpl = new template($phpapi->options['doclet'], 'header.tpl');
        $output['title'] = $phpapi->options['docTitle'];
        ob_start();

        echo $tpl->parse($output);

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('header.html', 'Header', FALSE);

        # Builds the footer frame
        $tpl = new template($phpapi->options['doclet'], 'footer.tpl');
        ob_start();

        echo $tpl->parse();

        $this->output = ob_get_contents();
        ob_end_clean();
        $this->write('footer.html', 'Footer', FALSE);
    }
}
