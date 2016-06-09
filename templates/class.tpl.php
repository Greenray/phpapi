<hr />
<div class="package">__Namespace__ $package</div>
<h1>$qualified</h1>
<div id="list">
    <ul>
        $tree
    </ul>
</div>
<!-- IF !empty($implements) -->
    <dl>
        <dt>__Interfaces__:</dt>
        <!-- FOREACH $implements -->
            <dd>$implements.name</dd>
        <!-- END -->
    </dl>
<!-- END -->
<!-- IF !empty($traits) -->
    <dl>
        <dt>__Traits__:</dt>
        <!-- FOREACH $traits -->
            <dd>$traits.name</dd>
        <!-- END -->
    </dl>
<!-- END -->
<!-- IF !empty($sections) -->
    <dl>
        <dt>__Subclasses__:</dt>
        <!-- FOREACH $subclasses -->
            <dd>$subclasses.name</dd>
        <!-- END -->
    </dl>
<!-- END -->
<hr />
<p class="arguments">$ismodifiers $is <strong>$isname</strong><!-- IF !empty($extends) -->$extends<!-- END --></p>
<div class="comment" id="overview_description">$textTag</div>
<!-- IF !empty($mainParams) -->$mainParams<!-- END -->
<!-- IF !empty($inheritFields) -->
    <!-- FOREACH $inheritFields -->
        <table class="inherit">
            <tr><th>__Fields inherited from__ $inheritFields.fullNamespace</th></tr>
            <tr>
                <td>
                <!-- FOREACH $inheritFields.name -->
                    <span><a href="$name.path"><span class="green">$name.name</span></a>&nbsp;</span>
                <!-- END -->
                </td>
            </tr>
        </table>
    <!-- END -->
<!-- END -->
<!-- IF !empty($inheritMethods) -->
    <!-- FOREACH $inheritMethods -->
        <table class="inherit">
            <tr><th>__Methods inherited from__ $inheritMethods.fullNamespace</th></tr>
            <tr>
                <td>
                <!-- FOREACH $inheritMethods.name -->
                    <span><a href="$name.path">$name.name</a>&nbsp;</span>
                <!-- END -->
                </td>
            </tr>
        </table>
    <!-- END -->
<!-- END -->
<!-- IF !empty($constants) -->
    <h2 id="details_constants">__Constants__</h2>
    <!-- FOREACH $constants -->
        <div class="location">$constants.location</div>
        <pre class="arguments" id="$constants.name">$constants.modifiers $constants.type <strong>$constants.name</strong>$constants.value</pre>
        <div class="details">$constants.fullDesc</div>
        <hr />
    <!-- END -->
<!-- END -->
<!-- IF !empty($fields) -->
    <h2 id="details_fields">__Fields__</h2>
    <!-- FOREACH $fields -->
        <div class="location">$fields.location</div>
        <pre class="arguments" id="$fields.id">$fields.modifiers $fields.type <strong><span class="green">$fields.name</span></strong>$fields.value</pre>
        <div class="details">$fields.fullDesc</div>
        <hr />
    <!-- END -->
<!-- END -->
<!-- IF !empty($constructor) -->
    <h2 id="details_constructor">__Constructor__</h2>
    <div class="location">$c_location</div>
    <code class="arguments" id="$c_name">$c_modifiers $c_type <strong>$c_name</strong>$c_arguments</code>
    <div class="details">
    <!-- IF !empty($c_includes) -->
        $c_includes
    <!-- END -->
        <p class="description">$c_fullDesc</p>
        $c_parameters
    </div>
<!-- END -->
<!-- IF !empty($destructor) -->
    <h2 id="details_destructor">__Destructor__</h2>
    <div class="location">$d_location</div>
    <code class="arguments" id="$d_name">$d_modifiers $d_type <strong>$d_name</strong>$d_arguments</code>
    <div class="details">
        $d_fullDesc
        $d_parameters
    </div>
<!-- END -->
<!-- IF !empty($methods) -->
    <h2 id="details_methods">__Methods__</h2>
    <!-- FOREACH $methods -->
        <div class="location">$methods.location</div>
        <code class="arguments" id="$methods.name">$methods.modifiers $methods.type <strong>$methods.name</strong> $methods.arguments</code>
        <div class="details">
        <!-- IF !empty($methods.includes) -->
            $methods.includes
        <!-- END -->
            <p class="description">$methods.fullDesc</p>
            $methods.parameters
        </div>
        <hr />
    <!-- END -->
<!-- END -->
