#!/usr/bin/php
<?php
/** phpapi: The PHP Documentation Creator
 * Copyright (C) 2014 - 2015 Victor Nabatov <greenray.spb@gmail.com>
 *
 * This program is a fork of the
 * PHPDoctor: The PHP Documentation Creator version 2.0.5
 * Copyright (C) 2005 Paul James <paul@peej.co.uk>
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @file      phpapi.php
 * @version   2.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 */

ini_set('display_errors', 1);
mb_internal_encoding('UTF-8');
setlocale(LC_CTYPE, ['ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);
setlocale(LC_ALL,   ['ru_RU.UTF-8', 'ru_UA.UTF-8', 'by_BY.UTF-8', 'en_US.UTF-8', 'en_GB.UTF-8']);
if (date_default_timezone_set(date_default_timezone_get()) === FALSE) {
    date_default_timezone_set('UTC');
}
error_reporting(E_ALL & ~E_DEPRECATED);

# Check we are running from the command line
if (!isset($argv[0])) {
    die('This program must be run from the command line using the CLI version of PHP');

# Check we are using the correct version of PHP
} elseif (!defined('T_COMMENT') || !extension_loaded('tokenizer') || version_compare(phpversion(), '5', '<')) {
    die('You need PHP version 5 or greater with the "tokenizer" extension to run this script, please upgrade');
}

/** Alias for DIRECTORY_SEPARATOR */
define('DS', DIRECTORY_SEPARATOR);
/** Alias for line feed */
define('LF', PHP_EOL);

# Undefined internal constants so we don't throw undefined constant errors later on
if (!defined('T_ABSTRACT'))     define('T_ABSTRACT',     0);
if (!defined('T_CONST'))        define('T_CONST',        0);
if (!defined('T_DOC_COMMENT'))  define('T_DOC_COMMENT',  0);
if (!defined('T_FINAL'))        define('T_FINAL',        0);
if (!defined('T_IMPLEMENTS'))   define('T_IMPLEMENTS',   0);
if (!defined('T_INTERFACE'))    define('T_INTERFACE',    0);
if (!defined('T_ML_COMMENT'))   define('T_ML_COMMENT',   0);
if (!defined('T_NAMESPACE'))    define('T_NAMESPACE',    0);
if (!defined('T_NS_C'))         define('T_NS_C',         0);
if (!defined('T_NS_SEPARATOR')) define('T_NS_SEPARATOR', 0);
if (!defined('T_PRIVATE'))      define('T_PRIVATE',      0);
if (!defined('T_PROTECTED'))    define('T_PROTECTED',    0);
if (!defined('T_PUBLIC'))       define('T_PUBLIC',       0);
if (!defined('T_THROW'))        define('T_THROW',        0);
if (!defined('T_TRAIT'))        define('T_TRAIT',        0);
if (!defined('T_USE'))          define('T_USE',          0);
if (!defined('GLOB_ONLYDIR'))   define('GLOB_ONLYDIR', FALSE);

/** System root */
define('ROOT', '.'.DS);
/** Doclets */
define('DOCLETS',    ROOT.'doclets'.DS);
/** Модули форматирования */
define('FORMATTERS', ROOT.'formatters'.DS);
/** Taglets */
define('TAGLETS',    ROOT.'taglets'.DS);
/** Templates */
define('TEMPLATES', './templates'.DS);
/** System classes */
define('CLASSES',    ROOT.'classes'.DS);
/** System locales */
define('LOCALES',    ROOT.'locales'.DS);
/** Version of the system */
define('VERSION', '2.0');
/** Copyright */
define('COPYRIGHT', '&copy; 2015 Greenray');
/** System generator */
define('GENERATOR', 'Generated by <a href="https://github.com/Greenray/phpAPI" target="_blank">phpAPI ver. '.VERSION.': The PHP Documentation Creator</a>');

# Load classes
require CLASSES.'doc.php';
require CLASSES.'rootDoc.php';
require CLASSES.'packageDoc.php';
require CLASSES.'elementDoc.php';
require CLASSES.'fieldDoc.php';
require CLASSES.'classDoc.php';
require CLASSES.'executableDoc.php';
require CLASSES.'methodDoc.php';
require CLASSES.'type.php';
require CLASSES.'tag.php';
require CLASSES.'template.php';

# Include phpapi class
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));

require CLASSES.'phpAPI.php';

/** String localization.
 * Currently, the system supports two languages: English and Russian.
 *
 * @global array  $lang   Array of language strings
 * @param  string $string String to be translated
 * @return string         Translated string
 */
global $lang;
function __($string) {
    global $lang;
    return empty($lang[$string]) ? $string : $lang[$string];
}

# Get name of config file to use
if (!isset($argv[1])) {
    if     (isset($_ENV['phpapi']))                     $argv[1] = $_ENV['phpapi'];
    elseif (is_file(getcwd().DS.'phpapi.ini'))          $argv[1] = getcwd().DS.'phpapi.ini';
    elseif (is_file(dirname(__FILE__).DS.'phpapi.ini')) $argv[1] = dirname(__FILE__).DS.'phpapi.ini';
    else {
        die("Usage: phpapi [config_file]".LF);
    }
}

$phpdoc = new phpapi($argv[1]);

if ($phpdoc->getOption('lang') !== 'russian') {
    include 'locales'.DS.$phpdoc->getOption('lang').'.php';
}

$rootDoc = $phpdoc->parse();
$phpdoc->execute($rootDoc);
