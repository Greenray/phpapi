<div class="package">__Namespace__ $package</div>
<h1>__Functions__</h1>
<hr />
<!-- IF !empty($functions) -->
    <table id="summary_functions" class="title">
        <tr><th colspan="2" class="title">__Functions__: __summary__</th></tr>
        <!-- FOREACH $functions -->
            <tr>
                <td class="type w_200">$functions.modifiers $functions.type</td>
                <td class="description">
                    <p><a href="#$functions.name"><span class="lilac">$functions.name</span></a>$functions.arguments</p>
                    <p class="description">$functions.shortDesc</p>
                </td>
            </tr>
        <!-- END -->
    </table>
    <h2 id="details_functions">__Functions__</h2>
    <!-- FOREACH $functions -->
        <div class="location">$functions.location</div>
        <code id="$functions.name" class="arguments">$functions.modifiers $functions.type <strong>$functions.name</strong> $functions.arguments</code>
        <div class="details">
            $functions.fullDesc
            $functions.parameters
        </div>
    <!-- END -->
<!-- END -->
