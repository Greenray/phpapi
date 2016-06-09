<?php
/**
 * Markdown interface -  A text-to-HTML conversion tool for web writers.
 *
 * @program   Markdown: A text-to-HTML conversion tool
 * @version   1.6.0
 * @autor     Michel Fortin https://michelf.ca/projects/php-markdown/
 * @copyright Copyright (c) 2004-2015 Michel Fortin
 * @file      /system/markdown/markdownInterface.php
 * @package   markdown
 */

#
# Markdown Parser Interface
#
interface markdownInterface {
  #
  # Initialize the parser and return the result of its transform method.
  # This will work fine for derived classes too.
  #
  public static function defaultTransform($text);
  #
  # Main function. Performs some preprocessing on the input text
  # and pass it through the document gamut.
  #
  public function transform($text);
}
