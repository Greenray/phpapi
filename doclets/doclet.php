<?php
# PhpAPI: The PHP Documentation Creator

/** The base doclet.
 * @file      doclets/doclet.php
 * @version   1.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   Doclets
 */

class doclet {

    /** Format a URL link.
     * @param str url
     * @param str text
     * @return str
     */
    public function formatLink($url, $text) {
        return $text.' ('.$url.')';
    }

    /** Format text as a piece of code.
     * @param str text
     * @return str
     */
    public function asCode($text) {
        return $text;
    }
}
