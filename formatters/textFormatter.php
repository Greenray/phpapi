<?php
# phpapi: The PHP Documentation Creator

/** The formatter base class.
 * @file      doclets/formatters/textFormatter.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package   Formatters
 */
class textFormatter {

    /** Returns the plain text value of the string, with all formatting information removed.
     * @param  str $text the raw input
     * @return str
     */
    public function toPlainText($text) {
        return $this->_removeWhitespace($text);
    }

    /** Returns the text with all recognized formatting directives applied.
     * Meaningful implementations are provided by subclasses.
     * @param  str $text the raw input
     * @return str
     */
    public function toFormattedText($text) {
        return $this->toPlainText($text);
    }

    /** Removes whitespace around newlines.
     * @param  str $text the raw input
     * @return str
     */
    public function _removeWhitespace($text) {
        $text = preg_replace("/[ \t]*\n[ \t]*/", LF, $text);
        return $text;
    }
}
