<?php
# PhpAPI: The PHP Documentation Creator

# load classes
require 'htmlWriter.php';
require 'frameOutputWriter.php';
require 'headerFrameWriter.php';
require 'packageIndexWriter.php';
require 'packageIndexFrameWriter.php';
require 'packageFrameWriter.php';
require 'footerFrameWriter.php';
require 'packageWriter.php';
require 'classWriter.php';
require 'functionWriter.php';
require 'globalWriter.php';
require 'indexWriter.php';
require 'deprecatedWriter.php';
require 'todoWriter.php';
require 'sourceWriter.php';

/** The standard doclet.
 * This doclet generates HTML output similar to that produced by the Javadoc standard doclet.
 * @file      doclets/standard/standard.php
 * @version   1.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Standard
 */

class standard extends Doclet {

    /** A reference to the root doc.
     * @var rootDoc
     */
    public $_rootDoc;

    /** The directory to place the generated files.
     * @var string
     */
    public $_destination;

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

    /** Specifies the text to be placed at the bottom of each output file.
     * The text will be placed at the bottom of the page, below the lower navigation bar.
     * @var string
     */
    public $_bottom = '';

    /** Create a class tree?
     * @var string
     */
    public $_tree = TRUE;

    /** Whether or not to parse the code with GeSHi and include the formatted files in the documentation.
     * @var boolean
     */
    public $_includeSource = TRUE;

    /** Doclet constructor.
     * @param RootDoc rootDoc
     * @param TextFormatter formatter
     */
    public function standard(&$rootDoc, $formatter) {
        # set doclet options
        $this->_rootDoc = & $rootDoc;
        $phpAPI  = & $rootDoc->phpAPI();
        $options = & $rootDoc->options();

        $this->formatter = $formatter;

        if (isset($options['destination'])) {
            $this->_destination = $phpAPI->makeAbsolutePath($options['destination'], $phpAPI->sourcePath());
        } elseif (isset($options['output_dir'])) {
            $this->_destination = $phpAPI->makeAbsolutePath($options['output_dir'], $phpAPI->sourcePath());
        } else {
            $this->_destination = $phpAPI->makeAbsolutePath('apidocs', $phpAPI->sourcePath());
        }
        $this->_destination = $phpAPI->fixPath($this->_destination);

        if (is_dir($this->_destination)) {
            $phpAPI->warning('Output directory already exists, overwriting');
        } else {
            mkdir($this->_destination);
        }
        $phpAPI->verbose('Setting output directory to "'.$this->_destination.'"');

        if (isset($options['windowtitle'])) {
            $this->_windowTitle = $options['windowtitle'];
        }
        if (isset($options['doctitle'])) {
            $this->_docTitle = $options['doctitle'];
        }
        if (isset($options['header'])) {
            $this->_header = $options['header'];
        }
        if (isset($options['footer'])) {
            $this->_footer = $options['footer'];
        }
        if (isset($options['bottom'])) {
            $this->_bottom = $options['bottom'];
        }
        if (isset($options['tree'])) {
            $this->_tree = $options['tree'];
        }
        if (isset($options['include_source'])) {
            $this->_includeSource = $options['include_source'];
        }
        if ($this->_includeSource) {
            include_once $options['geshi'];
            if (!class_exists('GeSHi')) {
                $phpAPI->warning('Cannot find GeSHi, not pretty printing source');
            }
        }
        # Write frame
        $frameOutputWriter = & new frameOutputWriter($this);
        # Write package overview frame
        $headerFrameWriter = & new headerFrameWriter($this);
        # Write overview summary
        $packageIndexWriter = & new packageIndexWriter($this);
        # Write package overview frame
        $packageIndexFrameWriter = & new packageIndexFrameWriter($this);
        # Write package summaries
        $packageWriter = & new packageWriter($this);
        # Write package frame
        $packageFrameWriter = & new packageFrameWriter($this);
        # Write package overview frame
        $footerFrameWriter = & new footerFrameWriter($this);
        # Write classes
        $classWriter = & new classWriter($this);
        # Write global functions
        $functionWriter = & new functionWriter($this);
        # Write global variables
        $globalWriter = & new globalWriter($this);
        # Write index
        $indexWriter = & new indexWriter($this);
        # Write deprecated index
        $deprecatedWriter = & new deprecatedWriter($this);
        # Write todo index
        $todoWriter = & new todoWriter($this);
        # Write source files
        if ($this->_includeSource) {
            $sourceWriter = & new sourceWriter($this);
        }
        # copy stylesheet
        $phpAPI->message('Copying stylesheet');
        copy($phpAPI->docletPath().'stylesheet.css', $this->_destination.'stylesheet.css');
        $this->_bottom = GENERATOR;
    }

    /** Return a reference to the root doc.
     * @return RootDoc
     */
    function &rootDoc() {
        return $this->_rootDoc;
    }

    /** Return a reference to the application object.
     * @return phpAPI
     */
    function &phpAPI() {
        return $this->_rootDoc->phpAPI();
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

    /** Return the text to be placed at the bottom of each output file.
     * The text will be placed at the bottom of the page, below the lower navigation bar.
     * @return str
     */
    public function bottom() {
        return $this->_bottom;
    }

    /** Return whether to create a class tree or not.
     * @return bool
     */
    public function tree() {
        return $this->_tree;
    }

    /** Should we be outputting the source code?
     * @return bool
     */
    public function includeSource() {
        return $this->_includeSource;
    }

    /** Format a URL link.
     * @param str url
     * @param str text
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
