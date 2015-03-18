<?php
# phpapi: The PHP Documentation Creator

# load classes
require 'htmlWriter.php';
require 'frameOutputWriter.php';
require 'packageIndexWriter.php';
require 'packageIndexFrameWriter.php';
require 'packageFrameWriter.php';
require 'packageWriter.php';
require 'classWriter.php';
require 'functionWriter.php';
require 'globalWriter.php';
require 'indexWriter.php';
require 'deprecatedWriter.php';
require 'todoWriter.php';

/** The standard doclet.
 * This doclet generates HTML output similar to that produced by the Javadoc standard doclet.
 *
 * @file      doclets/standard/standard.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Standard
 */

class standard {

    /** A reference to the root doc.
     * @var rootDoc
     */
    public $_rootDoc;

    /** The directory to place the generated files.
     * @var string
     */
    public $_destination = 'api';

    /** Specifies the title to be placed in the HTML <title> tag.
     * @var string
     */
    public $_windowTitle = 'The Unknown Project';

    /** Specifies the title to be placed near the top of the overview summary file.
     * @var string
     */
    public $_docTitle = 'The Unknown Project';

    /** Specifies the header text to be placed at the top of each output file.
     * The header will be placed to the right of the upper navigation bar.
     * @var string
     */
    public $_header = 'Unknown';

    /** Specifies the footer text to be placed at the bottom of each output file.
     * The footer will be placed to the right of the lower navigation bar.
     * @var string
     */
    public $_footer = 'Unknown';

    /** Create a class tree?
     * @var string
     */
    public $_tree = TRUE;

    /** Doclet constructor.
     * @param rootDoc rootDoc
     * @param TextFormatter formatter
     */
    public function standard(&$rootDoc, $formatter) {
        # set doclet options
        $this->_rootDoc =& $rootDoc;
        $phpapi  =& $rootDoc->phpapi();
        $options =& $rootDoc->options();

        $this->formatter = $formatter;

        if (isset($options['destination']))
             $this->_destination = $phpapi->makeAbsolutePath($options['destination'], $phpapi->sourcePath());
        else $this->_destination = $phpapi->makeAbsolutePath($this->_destination,     $phpapi->sourcePath());

        $this->_destination = $phpapi->fixPath($this->_destination);

        if (is_dir($this->_destination))
             $phpapi->warning('Output directory already exists, overwriting');
        else mkdir($this->_destination);

        $phpapi->verbose('Setting output directory to "'.$this->_destination.'"');

        if (isset($options['windowtitle'])) $this->_windowTitle = $options['windowtitle'];
        if (isset($options['doctitle']))    $this->_docTitle    = $options['doctitle'];
        if (isset($options['header']))      $this->_header      = $options['header'];
        if (isset($options['footer']))      $this->_footer      = $options['footer'];
        if (isset($options['tree']))        $this->_tree        = $options['tree'];

        $frameOutputWriter =& new frameOutputWriter($this); # Main frame

        echo '<body>';
        echo '<div id="header"><h1>'.$this->docTitle().'</h1></div>';
        echo '</body>';

        $packageIndexWriter      =& new packageIndexWriter($this);      # Overview summary
        $packageIndexFrameWriter =& new packageIndexFrameWriter($this); # Package overview frame
        $packageWriter           =& new packageWriter($this);           # Package summaries
        $packageFrameWriter      =& new packageFrameWriter($this);      # Package frame
        $classWriter             =& new classWriter($this);             # Classes
        $functionWriter          =& new functionWriter($this);          # Global functions
        $globalWriter            =& new globalWriter($this);            # Global variables
        $indexWriter             =& new indexWriter($this);             # Index
        $deprecatedWriter        =& new deprecatedWriter($this);        # Deprecated index
        $todoWriter              =& new todoWriter($this);              # Todo index

        $phpapi->message('Copying stylesheet');
        copy($phpapi->docletPath().'stylesheet.css', $this->_destination.'stylesheet.css');
    }

    /** Return a reference to the root doc.
     * @return rootDoc
     */
    function &rootDoc() {
        return $this->_rootDoc;
    }

    /** Return a reference to the application object.
     * @return phpapi
     */
    function &phpapi() {
        return $this->_rootDoc->phpapi();
    }

    /** Get the destination path to write the doclet output to.
     * @return str
     */
    public function destinationPath() {
        return $this->_destination;
    }

    /** Return the title to be placed in the HTML <title> tag.
     * @return str
     */
    public function windowTitle() {
        return $this->_windowTitle;
    }

    /** Return the title to be placed near the top of the overview summary file.
     * @return str
     */
    public function docTitle() {
        return $this->_docTitle;
    }

    /** Return the header text to be placed at the top of each output file.
     * The header will be placed to the right of the upper navigation bar.
     * @return str
     */
    public function getHeader() {
        return $this->_header;
    }

    /** Return the footer text to be placed at the bottom of each output file.
     * The footer will be placed to the right of the lower navigation bar.
     * @return str
     */
    public function getFooter() {
        return $this->_footer;
    }

    /** Return whether to create a class tree or not.
     * @return bool
     */
    public function tree() {
        return $this->_tree;
    }

    /** Format a URL link.
     * @param string url
     * @param string text
     */
    public function formatLink($url, $text) {
        return '<a href="'.$url.'">'.$text.'</a>';
    }

    /** Format text as a piece of code.
     * @param  string $text
     * @return string
     */
    public function asCode($text) {
        return '<code>'.$text.'</code>';
    }
}
