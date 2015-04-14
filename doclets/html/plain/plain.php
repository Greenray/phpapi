<?php
/** The plain doclet.
 * This doclet generates HTML output without frames.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      doclets/html/plain/plain.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   plain
 * @overview  The frames doclet.
 *            This doclet generates HTML output without frames.
 */
class plain {

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

        require 'items.php';
        require 'classItems.php';

        if (isset($rootDoc->phpapi->options['destination']))
             $this->destination = $phpapi->makeAbsolutePath($rootDoc->phpapi->options['destination'], $phpapi->sourcePath());
        else $this->destination = $phpapi->makeAbsolutePath($this->destination,                       $phpapi->sourcePath());

        $this->destination = $phpapi->fixPath($this->destination);

        if (is_dir($this->destination))
             $phpapi->warning('Output directory already exists, overwriting');
        else mkdir($this->destination);

        $phpapi->verbose('Setting output directory to "'.$this->destination.'"');

        if (isset($rootDoc->phpapi->options['header'])) $this->header = $rootDoc->phpapi->options['header'];

        $overviewSummaryWriter = &new overviewSummaryWriter($this, 'index');    # Overview summary
        $packageWriter         = &new packageWriter($this,         'index');    # Package summaries
        $classWriter           = &new classWriter($this,           'index');    # Classes
        $functionWriter        = &new functionWriter($this,        'index');    # Global functions
        $globalWriter          = &new globalWriter($this,          'index');    # Global variables
        $indexWriter           = &new indexWriter($this,           'index');    # Index
        $deprecatedWriter      = &new deprecatedWriter($this,      'index');    # Deprecated index
        $todoWriter            = &new todoWriter($this,            'index');    # Todo index

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
