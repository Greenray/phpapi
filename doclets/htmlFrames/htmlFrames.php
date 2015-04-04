<?php
# load classes
require 'htmlWriter.php';
require 'frameOutputWriter.php';
require 'overviewSummaryWriter.php';
require 'overviewFrameWriter.php';
require 'packageFrameWriter.php';
require 'packageWriter.php';
require 'classWriter.php';
require 'functionWriter.php';
require 'globalWriter.php';
require 'indexWriter.php';
require 'deprecatedWriter.php';
require 'todoWriter.php';

/** The htmlFrames doclet.
 * This doclet generates HTML output similar to that produced by the Javadoc htmlFrames doclet.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/htmlFrames/htmlFrames.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   htmlFrames
 */

class htmlFrames {

    /** The directory to place the generated files.
     * @var string
     */
    public $destination = 'api';

    /** Specifies the footer text to be placed at the bottom of each output file.
     * The footer will be placed to the right of the lower navigation bar.
     * @var string
     */
    public $footer = 'Unknown';

    /** Specifies the header text to be placed at the top of each output file.
     * The header will be placed to the right of the upper navigation bar.
     * @var string
     */
    public $header = 'Unknown';

    /** A reference to the root doc.
     * @var rootDoc
     */
    public $rootDoc;

    /** Doclet constructor.
     *
     * @param rootDoc rootDoc
     * @param TextFormatter formatter
     */
    public function htmlFrames(&$rootDoc, $formatter) {
        # set doclet options
        $this->rootDoc = &$rootDoc;
        $phpapi  = &$rootDoc->phpapi();
        $options = &$rootDoc->options();

        $this->formatter = $formatter;

        if (isset($options['destination']))
             $this->destination = $phpapi->makeAbsolutePath($options['destination'], $phpapi->sourcePath());
        else $this->destination = $phpapi->makeAbsolutePath($this->destination,      $phpapi->sourcePath());

        $this->destination = $phpapi->fixPath($this->destination);

        if (is_dir($this->destination))
             $phpapi->warning('Output directory already exists, overwriting');
        else mkdir($this->destination);

        $phpapi->verbose('Setting output directory to "'.$this->destination.'"');

        if (isset($options['header']))      $this->header      = $options['header'];
        if (isset($options['footer']))      $this->footer      = $options['footer'];

        $frameOutputWriter = &new frameOutputWriter($this); # Main frame

        echo '<body>';
        echo '<div id="header"><h1>'.$phpapi->options['docTitle'].'</h1></div>';
        echo '</body>';

        $overviewSummaryWriter = &new overviewSummaryWriter($this);   # Overview summary
        $overviewFrameWriter   = &new overviewFrameWriter($this);     # Packages overview frame
        $packageWriter         = &new packageWriter($this);           # Package summaries
        $packageFrameWriter    = &new packageFrameWriter($this);      # Package frame
        $classWriter           = &new classWriter($this);             # Classes
        $functionWriter        = &new functionWriter($this);          # Global functions
        $globalWriter          = &new globalWriter($this);            # Global variables
        $indexWriter           = &new indexWriter($this);             # Index
        $deprecatedWriter      = &new deprecatedWriter($this);        # Deprecated index
        $todoWriter            = &new todoWriter($this);              # Todo index

        $phpapi->verbose('Copying stylesheet');
        copy($phpapi->docletPath().'style.css', $this->destination.'style.css');

        if (!is_dir($this->destination.'resources')) mkdir($this->destination.'resources');

        $phpapi->verbose('Copying resources');
        $dir = dir(RESOURCES);
        if ($dir) {
            $exclude = ['.', '..'];
            while (($element = $dir->read()) !== FALSE) {
                if (!in_array($element, $exclude)) {
                    if (is_readable(RESOURCES.$element)) {
                        copy(RESOURCES.$element, $this->destination.'resources'.DS.$element);
                    }
                }
            }
            $dir->close();
        }
    }

    /** Returns a reference to the root doc.
     * @return rootDoc
     */
    function &rootDoc() {
        return $this->rootDoc;
    }

    /** Returns a reference to the application object.
     * @return phpapi
     */
    function &phpapi() {
        return $this->rootDoc->phpapi();
    }

    /** Returns the header text to be placed at the top of each output file.
     * The header will be placed to the right of the upper navigation bar.
     * @return str
     */
    public function getHeader() {
        return $this->header;
    }

    /** Returns the footer text to be placed at the bottom of each output file.
     * The footer will be placed to the right of the lower navigation bar.
     * @return str
     */
    public function getFooter() {
        return $this->footer;
    }

    /** Returns whether to create a class tree or not.
     * @return bool
     */
    public function tree() {
        return $this->tree;
    }

    /** Format a URL link.
     *
     * @param string url
     * @param string text
     */
    public function formatLink($url, $text) {
        return '<a href="'.$url.'">'.$text.'</a>';
    }

    /** Format text as a piece of code.
     *
     * @param  string $text
     * @return string
     */
    public function asCode($text) {
        return '<code>'.$text.'</code>';
    }
}
