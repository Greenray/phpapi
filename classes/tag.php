<?php
# phpapi: The PHP Documentation Creator

/** Represents a documentation tag, e.g. @since, @author, @version.
 * Given a tag (e.g. "@since 1.2"), holds tag name (e.g. "@since") and tag text (e.g. "1.2").
 * Tags with structure or which require special processing are handled by subclasses.
 *
 * @file      classes/Tag.php
 * @version   1.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   Tags
 */

class tag {

    /** The name of the tag.
     * @var string
     */
    public $_name = NULL;

    /** The value of the tag as raw data, without any text processing applied.
     * @var string
     */
    public $_text = NULL;

    /** Reference to the root element.
     * @var rootDoc
     */
    public $_root = NULL;

    /** Type of parameter.
     * @var string
     */
    public $_type = NULL;

    /** Reference to the elements parent.
     * @var programElementDoc
     */
    public $_parent = NULL;

    /** Constructor.
     *
     * @param string name The name of the tag (including @)
     * @param string text The contents of the tag
     * @param rootDoc root The root object
     */
    public function tag($name, $text, &$root, $type = '') {
        $this->_name = $name;
        $this->_root =& $root;
        $this->_text = $text;
        $this->_type = $type;
    }

    /** Gets name of this tag.
     * @return str
     */
    public function name() {
        return $this->_name;
    }

    /** Gets display name of this tag.
     * @return str
     */
    public function displayName() {
        return ucfirst(substr($this->_name, 1));
    }

    /** Gets the value of the tag as raw data, without any text processing applied.
     *
     * @param Doclet doclet
     * @return str
     */
    public function text($doclet) {
        return $this->_text;
    }

    /** Gets type of this tag.
     * @return str
     */
    public function type() {
        return $this->_type;
    }

    /** Sets this tags parent
     * @param ProgramElementDoc element The parent element
     */
    public function setParent(&$element) {
        $this->_parent =& $element;
    }

    /** For documentation comment with embedded @link tags, return the array of tags.
     * Within a comment string "This is an example of inline tags for a
     * documentaion comment {@link Doc commentlabel}", where inside the inner
     * braces, the first "Doc" carries exactly the same syntax as a SeeTag and
     * the second "commentlabel" is label for the HTML link, will return an array
     * of tags with first element as tag with comment text "This is an example of
     * inline tags for a documentation comment" and second element as SeeTag with
     * referenced class as "Doc" and the label for the HTML link as "commentlabel".
     *
     * @return Tag[] Array of tags with inline tags.
     * @todo This method does not act as described but should be altered to do so
     */
    function &inlineTags($formatter) {
        return $this->_getInlineTags($this->text($formatter));
    }

    /** Returns the first sentence of the comment as tags.
     * Includes inline tags (i.e. {@link reference} tags) but not regular tags.
     * Each section of plain text is represented as a Tag of kind "Text".
     * Inline tags are represented as a SeeTag of kind "link".
     * The sentence ends at the first period that is followed by a space, tab,
     * or a line terminator, at the first tagline, or closing of a HTML block element
     * (<p> <h1> <h2> <h3> <h4> <h5> <h6> <hr> <pre>).
     *
     * @return Tag[] An array of Tags representing the first sentence of the comment
     * @todo This method does not act as described but should be altered to do so
     */
    function &firstSentenceTags($formatter) {
        $phpapi     = $this->_root->phpapi();
        $matches    = [];
        $expression = '/^(.+)(\.(?: |\t|\n|<\/p>|<\/?h[1-6]>|<hr)|$)/sU';
        if (preg_match($expression, $this->text($formatter), $matches))
              $return =& $this->_getInlineTags($matches[1].$matches[2]);
        else  $return = [&$this];

        return $return;
    }

    /** Parses out inline tags from within a text string.
     *
     * @param string $text Text for parse
     * @return Tag[]       Array of parsed tags
     */
    function &_getInlineTags($text) {
        $return     = NULL;
        $tagStrings = preg_split('/{(@.+)}/sU', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($tagStrings) {
            $inlineTags = NULL;
            $phpapi =& $this->_root->phpapi();
            foreach ($tagStrings as $tag) {
                if (substr($tag, 0, 1) === '@') {
                    $pos = strpos($tag, ' ');
                    if ($pos !== FALSE) {
                        $name = trim(substr($tag, 0, $pos));
                        $text = trim(substr($tag, $pos + 1));
                    } else {
                        $name = $tag;
                        $text = NULL;
                    }
                } else {
                    $name    = '@text';
                    $strings = explode(LF, $tag);
                    if (!empty($strings[1])) {
                        $tag  = array_shift($strings).LF;
                        $tag .= implode(LF, $strings);
                    }
                    $text = $tag;
                }
                $data = NULL;
                $inlineTag =& $phpapi->createTag($name, $text, $data, $this->_root);
                $inlineTag->setParent($this->_parent);
                $inlineTags[] = $inlineTag;
            }
            $return =& $inlineTags;
        }
        return $return;
    }

    /** Returns true if this Taglet is used in constructor documentation.
     * @return bool
     */
    public function inConstructor() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in field documentation.
     * @return bool
     */
    public function inField() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in method documentation.
     * @return bool
     */
    public function inMethod() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in overview documentation.
     * @return bool
     */
    public function inOverview() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in package documentation.
     * @return bool
     */
    public function inPackage() {
        return TRUE;
    }

    /** Returns true if this Taglet is used in class or interface documentation.
     * @return bool
     */
    public function inType() {
        return TRUE;
    }

    /** Returns true if this Taglet is an inline tag.
     * @return bool
     */
    public function isInlineTag() {
        return FALSE;
    }

    /** Returns true if this Taglet should be outputted even if it has no text content.
     * @return bool
     */
    public function displayEmpty() {
        return TRUE;
    }
}
