<?php
/** Represents a PHP function, method (member function) or constructor.
 *
 * @program   phpapi: The PHP Documentation Creator
 * @file      classes/executableDoc.php
 * @version   3.1
 * @author    Victor Nabatov greenray.spb@gmail.com
 * @copyright (c) 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
 * @package   phpapi
 * @abstract
 */

class executableDoc extends elementDoc {

    /** The parameters this function takes.
     * @var fieldDoc[]
     */
    public $parameters = [];

    /** The subfunctions this function has.
     * @var methodDoc[]
     */
    public $functions = [];

    /** The exceptions this function throws.
     * @var classDoc[]
     */
    public $throws = [];

    /** Add a subfunction to this function.
     * @param methodDoc function
     */
    public function addMethod(&$function) {
        $this->functions[$function->name()] = &$function;
    }

    /** Gets argument information.
     * @return fieldDoc[] An array of parameter, one element per argument in the
     * order the arguments are present
     */
    function &parameters() {
        return $this->parameters;
    }

    /** Gets subfunctions.
     * @return methodDoc[] An array of subfunctions.
     */
    function &functions() {
        return $this->functions;
    }

    /** Returns exceptions this function throws.
     * @return classDoc[]
     */
    function &thrownExceptions() {
        return $this->throws;
    }

    /** Returns the param tags in this function.
     * @return NULL|Tag[]
     */
    public function paramTags() {
        if (isset($this->tags['@param'])) {
            if (is_array($this->tags['@param']))
                 return $this->tags['@param'];
            else return array($this->tags['@param']);
        } else return NULL;
    }

    /** Returns the throws tags in this function.
     * @return Type
     */
    public function throwsTags() {
        if (isset($this->tags['@throws'])) {
            if (is_array($this->tags['@throws']))
                 return $this->tags['@throws'];
            else return array($this->tags['@throws']);
        } else return NULL;
    }

    /** Gets signature.
     * Return a string which is the flat signiture of this function.
     * It is the parameter list, type is not qualified.
     * <pre>
     * for a function
     *      mymethod(foo x, integer y)
     * it will return
     *      (foo x, integer y)
     * </pre>
     * Recognised types are turned into HTML anchor tags to the documentation
     * page for the class defining them.
     * @return str
     */
    public function signature() {
        $signature = '';
        $myPackage = &$this->containingPackage();
        foreach ($this->parameters as $param) {
            $type = &$param->type();
            $classDoc = &$type->asClassDoc();
            if ($classDoc) {
                $packageDoc = &$classDoc->containingPackage();
                $signature .= '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name().'</a> '.$param->name().', ';
            } else {
                $signature .= '<span class="lilac">'.$type->typeName().'</span> <span class="blue">$'.$param->name().'</span>, ';
            }
        }
        return '('.substr($signature, 0, -2).')';
    }
}
