<?php
/**
 * Plain doclet.
 * This doclet generates HTML output without frames.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      doclets/html/plain/plain.php
 * @version   5.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons — Attribution-NonCommercial-ShareAlike 4.0 International
 * @package   plain
 * @overview  Frames doclet.
 *            This doclet generates HTML output without frames.
 */
class plain {

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

        $this->rootDoc   = $rootDoc;
        $this->formatter = $formatter;
        $phpapi = &$rootDoc->phpapi;

        require 'items.php';
        require 'classItems.php';

        if (is_dir($phpapi->options['destination']))
             $phpapi->warning('Output directory already exists, overwriting');
        else mkdir($phpapi->options['destination']);

        $phpapi->verbose('Setting output directory to "'.$phpapi->options['destination'].'"');

        if (isset($phpapi->options['header'])) $this->header = &$phpapi->options['header'];

        $overviewSummaryWriter = &new overviewSummaryWriter($this, 'index');    # Overview summary
        $packageWriter         = &new packageWriter($this,         'index');    # Package summaries
        $classWriter           = &new classWriter($this,           'index');    # Classes
        $functionWriter        = &new functionWriter($this,        'index');    # Global functions
        $globalWriter          = &new globalWriter($this,          'index');    # Global variables
        $indexWriter           = &new indexWriter($this,           'index');    # Index
        $deprecatedWriter      = &new deprecatedWriter($this,      'index');    # Deprecated index
        $todoWriter            = &new todoWriter($this,            'index');    # Todo index

        $phpapi->verbose('Copying stylesheet');
        copy(TEMPLATES.$phpapi->options['generator'].DS.$phpapi->options['doclet'].DS.'style.css', $phpapi->options['destination'].'style.css');

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
