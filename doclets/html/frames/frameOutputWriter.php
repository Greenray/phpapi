<?php
/**
 * This generates files used for presenting the frame-formated "cover page" of the API documentation.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @file      doclets/html/frames/frameOutputWriter.php
 * @package   frames
 */

class frameOutputWriter extends htmlWriter {

    /**
     * Builds the HTML frameset.
     *
     * @param doclet &$doclet Reference to the documentation generator
     * @return string
     */
    public function __construct(&$doclet) {
        parent::htmlWriter($doclet);

        $phpapi = &$doclet->rootDoc->phpapi;
        $tpl = new template();
        #
        # Builds the main frameset
        #
        $this->output = $tpl->parse($phpapi, 'frame-output');
        $this->write('index.html', FALSE, FALSE);
        #
        # Builds the header frame
        #
        $tpl = new template();
        $tpl->set('title', $phpapi->options['docTitle']);
        $this->output = $tpl->parse($phpapi, 'header');
        $this->write('header.html', 'Header', FALSE);
        #
        # Builds the footer frame
        #
        $tpl = new template();
        $this->output = $tpl->parse($phpapi, 'footer');
        $this->write('footer.html', 'Footer', FALSE);
    }
}
