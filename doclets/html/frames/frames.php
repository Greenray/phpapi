<?php
/** The frames doclet.
 * This doclet generates HTML output similar to that produced by the Javadoc frames doclet.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/frames/frames.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   frames
 * @overview  The frames doclet.
 *            This doclet generates HTML output similar to that produced by the Javadoc htmlFrames doclet.
 */

class frames {

    /** @var string The directory to place the generated files */
    public $destination = 'api';

    /** Specifies the header text to be placed at the top of each output file.
     * The header will be placed to the right of the upper navigation bar.
     * @var string
     */
    public $header = 'Unknown';

    /** @var rootDoc A reference to the root doc */
    public $rootDoc;

    /** Doclet constructor.
     * @param rootDoc       &$rootDoc  The reference to the root document
     * @param htmlFormatter $formatter The documentation formatter
     */
    public function __construct(&$rootDoc, $formatter) {
        # set doclet options
        $this->rootDoc   = &$rootDoc;
        $this->formatter = $formatter;
        $phpapi = &$rootDoc->phpapi;

        require 'frameOutputWriter.php';
        require 'overviewFrameWriter.php';
        require 'packageFrameWriter.php';

        if (isset($phpapi->options['destination'])) {
            $this->destination = $phpapi->options['destination'];
        }
        $this->destination = $phpapi->fixPath($this->destination);

        if (is_dir($this->destination))
             $phpapi->warning('Output directory already exists, overwriting');
        else mkdir($this->destination);

        $phpapi->verbose('Setting output directory to "'.$this->destination.'"');

        if (isset($rootDoc->phpapi->options['header'])) $this->header = &$rootDoc->phpapi->options['header'];

        $frameOutputWriter     = &new frameOutputWriter($this);                           # Main frame
        $overviewSummaryWriter = &new overviewSummaryWriter($this, 'overview-summary');   # Overview summary
        $overviewFrameWriter   = &new overviewFrameWriter($this);                         # Packages overview frame
        $packageWriter         = &new packageWriter($this,         'overview-summary');   # Package summaries
        $packageFrameWriter    = &new packageFrameWriter($this);                          # Package frame
        $classWriter           = &new classWriter($this,           'overview-summary');   # Classes
        $functionWriter        = &new functionWriter($this,        'overview-summary');   # Global functions
        $globalWriter          = &new globalWriter($this,          'overview-summary');   # Global variables
        $indexWriter           = &new indexWriter($this,           'overview-summary');   # Index
        $deprecatedWriter      = &new deprecatedWriter($this,      'overview-summary');   # Deprecated index
        $todoWriter            = &new todoWriter($this,            'overview-summary');   # Todo index

        $phpapi->verbose('Copying stylesheet');
        copy(TEMPLATES.$rootDoc->phpapi->options['doclet'].DS.'style.css', $this->destination.'style.css');

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
}
