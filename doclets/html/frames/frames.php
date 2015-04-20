<?php
/**
 * Frames doclet.
 * This doclet generates HTML output similar to that produced by the Javadoc frames doclet.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      doclets/html/frames/frames.php
 * @version   4.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   frames
 * @overview  Frames doclet.
 *            This doclet generates HTML output similar to that produced by the Javadoc htmlFrames doclet.
 */

class frames {

    /**
     * Specifies the header text to be placed at the top of each output file.
     * Header will be placed to the right of the upper navigation bar.
     *
     * @var string
     */
    public $header = 'Unknown';

    /** @var rootDoc Reference to the root doc */
    public $rootDoc;

    /**
     * Doclet constructor.
     *
     * @param rootDoc       &$rootDoc  Reference to the root document
     * @param htmlFormatter $formatter Documentation formatter
     */
    public function __construct(&$rootDoc, $formatter) {

        $this->rootDoc   = &$rootDoc;
        $this->formatter = $formatter;
        $phpapi = &$rootDoc->phpapi;

        require 'frameOutputWriter.php';
        require 'overviewFrameWriter.php';
        require 'packageFrameWriter.php';

        if (is_dir($phpapi->options['destination']))
             $phpapi->warning('Output directory already exists, overwriting');
        else mkdir($phpapi->options['destination']);

        $phpapi->verbose('Setting output directory to "'.$phpapi->options['destination'].'"');

        if (isset($phpapi->options['header'])) $this->header = &$phpapi->options['header'];

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
        copy(TEMPLATES.$phpapi->options['doclet'].DS.'style.css', $phpapi->options['destination'].'style.css');

        if (!is_dir($phpapi->options['destination'].'resources')) mkdir($phpapi->options['destination'].'resources');

        $phpapi->verbose('Copying resources');
        $dir = dir(RESOURCES);
        if ($dir) {
            $exclude = ['.', '..'];
            while (($element = $dir->read()) !== FALSE) {
                if (!in_array($element, $exclude)) {
                    if (is_readable(RESOURCES.$element)) {
                        copy(RESOURCES.$element, $phpapi->options['destination'].'resources'.DS.$element);
                    }
                }
            }
            $dir->close();
        }
    }
}
