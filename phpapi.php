#!/usr/bin/php
<?php
/**
 * phpapi: PHP Documentation Creator
 * Copyright (c) 2015-2016 Victor Nabatov <greenray.spb@gmail.com>
 *
 * This program is a fork of the
 * PHPDoctor: PHP Documentation Creator version 2.0.5
 * Copyright (C) 2005 Paul James <paul@peej.co.uk>
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * Creative Commons Attribution-ShareAlike 4.0 International License
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @program   phpapi: PHP Documentation Creator
 * @version   6.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015-2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International
 * @file      phpapi.php
 * @package   phpapi
 */
#
# Check we are running from the command line
#
if (!isset($argv[0])) {
    die('This program must be run from the command line using the CLI version of PHP.');
}
#
# Check we are using the correct version of PHP
#
elseif (version_compare(phpversion(), '5.4.1', '<') || !extension_loaded('tokenizer')) {
    die('You need PHP version 5.4 or greater with the "tokenizer" extension to run this script, please upgrade.');
}

ini_set('display_errors', 1);
mb_internal_encoding('UTF-8');
setlocale(LC_CTYPE, ['ru_RU.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);
setlocale(LC_ALL,   ['ru_RU.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);

error_reporting(E_ALL & ~E_DEPRECATED);

/** Alias for DIRECTORY_SEPARATOR */
define('DS', DIRECTORY_SEPARATOR);
/** Alias for line feed */
define('LF', PHP_EOL);

/** System root */
define('ROOT', '.'.DS);
/** System */
define('SYSTEM',     ROOT.'system'.DS);
/** System classes */
define('CLASSES',    SYSTEM.'classes'.DS);
/** System locales */
define('LOCALES',    ROOT.'locales'.DS);
/** Images, javascripts and other resources */
define('RESOURCES',  ROOT.'resources'.DS);
/** Taglets */
define('TAGLETS',    SYSTEM.'taglets'.DS);
/** Templates */
define('TEMPLATES',  ROOT.'templates'.DS);
/** Markdown formatter */
define('MARKDOWN',   SYSTEM.'markdown'.DS);
/** Standard error output. */
if (!defined('STDERR')) define('STDERR', fopen("php://stderr", "wb"));

/** Version of the system */
define('VERSION', '6.0');
/** Copyright */
define('COPYRIGHT', '&copy; 2015 - 2016 Greenray');
/** System generator */
define('GENERATOR', 'Generated by <a href="https://github.com/Greenray/phpAPI" target="_blank">phpAPI</a>: PHP Documentation Creator ver. '.VERSION);

require CLASSES.'doc.php';
require CLASSES.'rootDoc.php';
require CLASSES.'elementDoc.php';
require CLASSES.'packageDoc.php';
require CLASSES.'classDoc.php';
require CLASSES.'methodDoc.php';
require CLASSES.'fieldDoc.php';
require CLASSES.'type.php';
require TAGLETS.'tag.php';
require CLASSES.'template.php';
require CLASSES.'phpAPI.php';

/** Website localization. */
global $lang;

/**
 * String localization.
 * Currently, the system supports two languages: English and Russian.
 *
 * @global  array  $lang   Array of language strings
 * @param   string $string String to be translated
 * @return  string         Translated string
 * @package phpapi
 */
function __($string) {
    global $lang;
    return empty($lang[$string]) ? $string : $lang[$string];
}

#
# Get name of config file to use
#

if (!isset($argv[1])) {
    if     (isset($_ENV['phpapi']))                     $argv[1] = $_ENV['phpapi'];
    elseif (is_file(getcwd().DS.'phpapi.ini'))          $argv[1] = getcwd().DS.'phpapi.ini';
    elseif (is_file(dirname(__FILE__).DS.'phpapi.ini')) $argv[1] = dirname(__FILE__).DS.'phpapi.ini';
    else {
        die("Usage: phpapi [config_file]".LF);
    }
}

$phpdoc  = new phpapi($argv[1]);
$rootDoc = $phpdoc->parse();
$phpdoc->execute($rootDoc);
