<?php
/**
 * HTML formatter.
 * Basic implementation, just deals with unordered lists for now.
 *
 * @program   phpapi: PHP Documentation Creator
 * @file      doclets/formatters/htmlFormatter.php
 * @version   4.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   formatters
 * @overview  Formatts output for doclets.
 *            At present time it works only with HTML.
 */

class htmlFormatter {

    /**
     * Detects unordered lists and adds the necessary markup.
     * Creates unordered lists. -, + are recogized as bullet points.
     *
     * @param  string $text Text to parse and modify
     * @return string       Parsed text
     */
    public function toPlainText($text) {
        if (!empty($text)) {
            # $li_rx: regex capturing a list entry, including those extending over multiple lines and those padded with empty lines
            # $ul_rx: regex capturing an unordered list - at least two list entries required
            $li_rx = '^([ \t]+([\-+])[ \t]+)(\S.*(?:\n [ \t]+(?!\2)(?![ \t]).*|\n[ \t]*)*\n)';
            $ul_rx = '(?:'.$li_rx.'){2,}';

            $text = preg_replace("/$ul_rx/m", "\n\n<ul>\n$0\n</ul>\n\n", $text);
            if (preg_match_all("#<ul>.*?</ul>#s", $text, $matches)) {
                $list = preg_replace("/$li_rx/m", '<li>$3</li>', $matches[0]);
                $text = str_replace($matches[0], $list, $text);
                #
                # Cleanup: Making sure that the lists won't appear inside a <p> (by removing double newlines around ul tags)
                # and won't have empty paragraphs in between list items (by removing double newlines between different li tags).
                #
                $text = preg_replace("#\s*<ul>\s*(<li>.+?</li>)\s*</ul>\s*#s", '<ul>$1</ul>', $text);
                $text = preg_replace("#\s*</li>\s*#", '</li>', $text);
            }
            $text = str_replace([LF.LF,LF], '<br />', $text);
        }
        return $text;
    }
}
