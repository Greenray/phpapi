<?php
/** Represents a documentation tag.
 * Given a tag (e.g. "@since 1.2"), holds tag name (e.g. "@since") and tag text (e.g. "1.2").
 * Tags with structure or which require special processing are handled by subclasses.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      taglets/tag.php
 * @version   4.0
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   tags
 * @overview  Represents a documentation tag.
 *            The tag is a keyword (e.g. @version) with value (e.g. 1.0)
 */

class tag {

    /** @var string The name of the tag */
    public $name = NULL;

    /** @var elementDoc The reference the elements parent */
    public $parent = NULL;

    /** @var rootDoc The reference the root element */
    public $root = NULL;

    /** @var string The value of the tag as raw data, without any text processing applied */
    public $text = NULL;

    /** @var string Type of parameter */
    public $type = NULL;

    /** Constructor.
     * @param  string  $name  The name of the tag (including @)
     * @param  string  $text  The contents of the tag
     * @param  rootDoc &$root The reference to the root object
     * @paqram string  $type  The type of the element
     */
    public function tag($name, $text, &$root, $type = '') {
        $this->name = $name;
        $this->root = &$root;
        $this->text = $text;
        $this->type = $type;
    }

    /** Gets display name of this tag.
     * @return string
     */
    public function displayName() {
        return ucfirst(substr($this->name, 1));
    }

    /** Returns the first sentence of the comment as tags.
     * Includes inline tags (i.e. {@link reference} tags) but not regular tags.
     * Each section of plain text is represented as a tag of kind "Text".
     * Inline tags are represented as a SeeTag of kind "link".
     * The sentence ends at the first period that is followed by a space, tab,
     * or a line terminator, at the first tagline, or closing of a HTML block elements.
     *
     * @return tag Tags object representing the first sentence of the comment
     * @todo   This method does not act as described but should be altered to do so
     */
    function &firstCommentString() {
        preg_match('/^(.+)(\.(?: |\t|\n|<\/p>|<\/?h[1-6]>|<hr)|$)/sU', $this->text, $matches);
        if ($matches)
              $return = &$this->getInlineTags($matches[0]);
        else  $return = [&$this];
        return $return;
    }

    /** Parses out inline tags from within a text string.
     * @param  string $text Text for parse
     * @return tag          Tags object of parsed tags
     */
    function &getInlineTags($text) {
        $return     = [];
        $tagStrings = preg_split('/{(@.+)}/sU', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($tagStrings) {
            $inlineTags = NULL;
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
                $inlineTag = &$this->root->phpapi->createTag($name, $text, $data, $this->root);
                $inlineTag->setParent($this->parent);
                $inlineTags[] = $inlineTag;
            }
            $return = &$inlineTags;
        }
        return $return;
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
     * @return tag  Tags object with inline tags.
     * @todo   This method does not act as described but should be altered to do so
     */
    function &inlineTags() {
        return $this->getInlineTags($this->text);
    }

    /** Sets this tags parent
     * @param elementDoc &$element The reference to the parent element
     */
    public function setParent(&$element) {
        $this->parent = &$element;
    }

    /** Returns TRUE if this Taglet is used in field documentation.
     * @return boolean
     */
    public function inField() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in method documentation.
     * @return boolean
     */
    public function inMethod() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in overview documentation.
     * @return boolean
     */
    public function inOverview() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in package documentation.
     * @return boolean
     */
    public function inPackage() {
        return TRUE;
    }

    /** Returns TRUE if this Taglet is used in class or interface documentation.
     * @return boolean
     */
    public function inType() {
        return TRUE;
    }
}