<?php
/** This generates the file used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/frames/frameOutputWriter.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   frames
 */

class frameOutputWriter extends htmlWriter {

    /** Builds the HTML frameset.
     * @param object &$doclet The reference to the documentation generator
     */
    public function __construct(&$doclet) {
        parent::htmlWriter($doclet);

        $phpapi = &$doclet->rootDoc->phpapi;
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
