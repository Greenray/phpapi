<hr />
<div class="package">[__Namespace] $package</div>
<h1>$qualified</h1>
<div id="list">
    <ul>
        $tree
    </ul>
</div>
<!-- IF !empty($implements) -->
    <dl>
        <dt>[__Interfaces]:</dt>
        <!-- FOREACH implement = $implements -->
            <dd>$implement.name</dd>
        <!-- ENDFOREACH -->
    </dl>
<!-- ENDIF -->
<!-- IF !empty($traits) -->
    <dl>
        <dt>[__Traits]:</dt>
        <!-- FOREACH trait = $traits -->
            <dd>$trait.name</dd>
        <!-- ENDFOREACH -->
    </dl>
<!-- ENDIF -->
<!-- IF !empty($sections) -->
    <dl>
        <dt>[__Subclasses]:</dt>
        <!-- FOREACH subclass = $subclasses -->
            <dd>$subclass.name</dd>
        <!-- ENDFOREACH -->
    </dl>
<!-- ENDIF -->
<hr />
<p class="arguments">$ismodifiers $is <strong>$isname</strong><!-- IF !empty($extends) -->$extends<!-- ENDIF --></p>
<div class="comment" id="overview_description">$textTag</div>
<!-- IF !empty($mainParams) -->$mainParams<!-- ENDIF -->
<!-- IF !empty($constants) -->
    <table id="summary_constants">
        <tr><th colspan="2">[__Constants]: [__summary]</th></tr>
        <!-- FOREACH constant = $constants -->
            <tr>
                <td class="type w_200">$constant.modifiers $constant.type</td>
                <td class="description">
                    <pre><a href="#$constant.name"><span class="lilac">$constant.name</span></a>$constant.value</pre>
                    <p class="description">$constant.shortDesc</p>
                </td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($fields) -->
    <table id="summary_fields">
        <tr><th colspan="2">[__Fiels]: [__summary]</th></tr>
        <!-- FOREACH field = $fields -->
            <tr>
                <td class="type w_200">$field.modifiers $field.type</td>
                <td class="description">
                    <pre><a href="#$field.name"><span class="green">$field.name</span></a><!-- IF !empty($fields) -->$field.value<!-- ENDIF --></pre>
                    <p class="description">$field.shortDesc</p>
                </td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($inheritFields) -->
    <!-- FOREACH inheritField = $inheritFields -->
        <table class="inherit">
            <tr><th>[__Fields inherited from] $inheritField.fullNamespace</th></tr>
            <tr>
                <td>
                <!-- FOREACH name = $inheritField.name -->
                <span><a href="$name.path"><span class="green">$name.name</span></a>&nbsp;</span>
                <!-- ENDFOREACH -->
                </td>
            </tr>
        </table>
    <!-- ENDFOREACH -->
<!-- ENDIF -->
<!-- IF !empty($constructor) -->
    <table id="summary_constructor">
        <tr><th colspan="2">[__Constructor]: [__summary]</th></tr>
        <tr>
            <td class="type w_200">$c_modifiers $c_type</td>
            <td class="description">
                <p><a href="#$c_name"><strong><span class="black">$c_name</span></strong></a>$c_arguments</p>
                <p class="description">$c_shortDesc</p>
            </td>
        </tr>
    </table>
<!-- ENDIF -->
<!-- IF !empty($destructor) -->
    <table id="summary_destructor">
        <tr><th colspan="2">[__Destructor]: [__summary]</th></tr>
        <tr>
            <td class="type w_200">$d_modifiers $d_type</td>
            <td class="description">
                <p><a href="#$d_name"><strong><span class="black">$d_name</span></strong></a>$d_arguments</p>
                <p class="description">$d_shortDesc</p>
            </td>
        </tr>
    </table>
<!-- ENDIF -->
<!-- IF !empty($methods) -->
    <table id="summary_methods">
        <tr><th colspan="2">[__Methods]: [__summary]</th></tr>
        <!-- FOREACH method = $methods -->
            <tr>
                <td class="type w_200">$method.modifiers $method.type</td>
                <td class="description">
                    <p><a href="#$method.name"><strong><span class="black">$method.name</span></strong></a> $method.arguments</p>
                    <p class="description">$method.shortDesc</p>
                </td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($inheritMethods) -->
    <!-- FOREACH inheritMethod = $inheritMethods -->
        <table class="inherit">
            <tr><th>[__Methods inherited from] $inheritMethod.fullNamespace</th></tr>
            <tr>
                <td>
                <!-- FOREACH name = $inheritMethod.name -->
                    <span><a href="$name.path">$name.name</a>&nbsp;</span>
                <!-- ENDFOREACH -->
                </td>
            </tr>
        </table>
    <!-- ENDFOREACH -->
<!-- ENDIF -->
<!-- IF !empty($constants) -->
    <h2 id="details_constants">[__Constants]: [__details]</h2>
    <!-- FOREACH constant = $constants -->
        <div class="location">$constant.location</div>
        <pre class="arguments" id="$constant.name">$constant.modifiers $constant.type <strong>$constant.name</strong>$constant.value</pre>
        <div class="details">$constant.fullDesc</div>
        <hr />
    <!-- ENDFOREACH -->
<!-- ENDIF -->
<!-- IF !empty($fields) -->
    <h2 id="details_fields">[__Fields]: [__details]</h2>
    <!-- FOREACH field = $fields -->
        <div class="location">$field.location</div>
        <pre class="arguments" id="$$field.id">$field.modifiers $field.type <strong><span class="green">$field.name</span></strong>$field.value</pre>
        <div class="details">$field.fullDesc</div>
        <hr />
    <!-- ENDFOREACH -->
<!-- ENDIF -->
<!-- IF !empty($constructor) -->
    <h2 id="details_constructor">[__Constructor]: [__details]</h2>
    <div class="location">$c_location</div>
    <code class="arguments" id="$c_name">$c_modifiers $c_type <strong>$c_name</strong>$c_arguments</code>
    <div class="details">
    <!-- IF !empty($c_includes) -->
        $c_includes
    <!-- ENDIF -->
        <p class="description">$c_fullDesc</p>
        $c_parameters
    </div>
<!-- ENDIF -->
<!-- IF !empty($destructor) -->
    <h2 id="details_destructor">[__Destructor]: [__details]</h2>
    <div class="location">$d_location</div>
    <code class="arguments" id="$d_name">$d_modifiers $d_type <strong>$d_name</strong>$d_arguments</code>
    <div class="details">
        $d_fullDesc
        $d_parameters
    </div>
<!-- ENDIF -->
<!-- IF !empty($methods) -->
    <h2 id="details_methods">[__Methods]: [__details]</h2>
    <!-- FOREACH method = $methods -->
        <div class="location">$method.location</div>
        <code class="arguments" id="$method.name">$method.modifiers $method.type <strong>$method.name</strong> $method.arguments</code>
        <div class="details">
        <!-- IF !empty($method.includes) -->
            $method.includes
        <!-- ENDIF -->
            <p class="description">$method.fullDesc</p>
            $method.parameters
        </div>
        <hr />
    <!-- ENDFOREACH -->
<!-- ENDIF -->
