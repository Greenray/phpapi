<?php
# phpAPI: The PHP Documentation Creator

/** Represents a PHP function, method (member function) or constructor.
 * @file      classes/executableDoc.php
 * @version   1.0
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2011 - 2015 Victor Nabatov
 * @license   Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>
 * @package   phpAPI
 * @abstract
 */

class executableDoc extends ProgramElementDoc {

    /** The parameters this function takes.
     * @var fieldDoc[]
     */
    public $_parameters = array();

    /** The subfunctions this function has.
     * @var methodDoc[]
     */
    public $_functions = array();

    /** The exceptions this function throws.
     * @var classDoc[]
     */
    public $_throws = array();

    /** Add a subfunction to this function.
     * @param MethodDoc function
     */
    public function addMethod(&$function) {
        $this->_functions[$function->name()] = & $function;
    }

    /** Get argument information.
     * @return FieldDoc[] An array of parameter, one element per argument in the
     * order the arguments are present
     */
    function &parameters() {
        return $this->_parameters;
    }

    /** Get subfunctions.
     * @return MethodDoc[] An array of subfunctions.
     */
    function &functions() {
        return $this->_functions;
    }

    /** Return exceptions this function throws.
     * @return ClassDoc[]
     */
    function &thrownExceptions() {
        return $this->_throws;
    }

    /** Return the param tags in this function.
     * @return NULL|Tag[]
     */
    public function paramTags() {
        if (isset($this->_tags['@param'])) {
            if (is_array($this->_tags['@param'])) {
                return $this->_tags['@param'];
            } else {
                return array($this->_tags['@param']);
            }
        } else {
            return NULL;
        }
    }

    /** Return the throws tags in this function.
     * @return Type
     */
    public function throwsTags() {
        if (isset($this->_tags['@throws'])) {
            if (is_array($this->_tags['@throws'])) {
                return $this->_tags['@throws'];
            } else {
                return array($this->_tags['@throws']);
            }
        } else {
            return NULL;
        }
    }

    /** Get the signature. It is the parameter list, type is qualified.
     * <pre>
     * for a function
     *      mymethod(foo x, int y)
     * it will return
     *      (bar.foo x, int y)
     * </pre>
     * Recognised types are turned into HTML anchor tags to the documentation
     * page for the class defining them.
     * @return str
     */
    public function signature() {
        $signature = '(';
        $myPackage = & $this->containingPackage();
        foreach ($this->_parameters as $param) {
            $type = $param->type();
            $classDoc = & $type->asClassDoc();
            if ($classDoc) {
                $packageDoc = & $classDoc->containingPackage();
                $signature .= '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->containingPackage().'.'.$classDoc->name().'</a> '.$param->name().$type->dimension().', ';
            } else {
                $signature .= $type->typeName().$type->dimension().', ';
            }
        }
        return substr($signature, 0, -2).')';
    }

    /** Get flat signature.
     * Return a string which is the flat signiture of this function.
     * It is the parameter list, type is not qualified.
     * <pre>
     * for a function
     *      mymethod(foo x, int y)
     * it will return
     *      (foo x, int y)
     * </pre>
     * Recognised types are turned into HTML anchor tags to the documentation
     * page for the class defining them.
     * @return str
     */
    public function flatSignature() {
        $signature = '';
        $myPackage = & $this->containingPackage();
        foreach ($this->_parameters as $param) {
            $type = & $param->type();
            $classDoc = & $type->asClassDoc();
            if ($classDoc) {
                $packageDoc = & $classDoc->containingPackage();
                $signature .= '<a href="'.str_repeat('../', $myPackage->depth() + 1).$classDoc->asPath().'">'.$classDoc->name().'</a> '.$param->name().', ';
            } else {
                $signature .= '<span class="lilac">'.$type->typeName().'</span> <span class="blue">$'.$param->name().'</span>, ';
            }
        }
        return '('.substr($signature, 0, -2).')';
    }
}
