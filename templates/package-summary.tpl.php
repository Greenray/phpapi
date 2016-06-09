<h1>__Namespace__ $namespace</h1>
<!-- IF !empty($shortView) -->
    <div class="comment">$shortView</div>
    <!-- IF !empty($overView) -->
        <dl><dt>__See__:</dt><dd><b><a href="#overview_description">__Description__</a></b></dd></dl>
    <!-- END -->
    <br />
<!-- END -->
<!-- IF !empty($interfaces) -->
    <table class="title">
        <tr><th colspan="2" class="title">__Interfaces__: __summary__</th></tr>
        <!-- FOREACH $interfaces -->
            <tr>
                <td class="name"><a href="$interfaces.path">$interfaces.name</a></td>
                <td class="description">$interfaces.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($traits) -->
    <table class="title">
        <tr><th colspan="2" class="title">__Traits__: __summary__</th></tr>
        <!-- FOREACH $traits -->
            <tr>
                <td class="name"><a href="$traits.path">$traits.name</a></td>
                <td class="description">$traits.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF isset($classes) && !empty($classes) -->
    <table class="title">
        <tr><th colspan="2" class="title">__Classes__: __summary__</th></tr>
        <!-- FOREACH $classes -->
            <tr>
                <td class="name"><a href="$classes.path">$classes.name</a></td>
                <td class="description">$classes.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($functions) -->
    <table class="title">
        <tr><th colspan="2" class="title">__Functions__: __summary__</th></tr>
        <!-- FOREACH $functions -->
            <tr>
                <td class="name"><a href="$functions.path">$functions.name</a></td>
                <td class="description">$functions.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($globals) -->
    <table class="title">
        <tr><th colspan="2" class="title">__Globals__: __summary__</th></tr>
        <!-- FOREACH $globals -->
            <tr>
                <td class="name"><a href="$globals.path">$globals.name</a></td>
                <td class="description">$globals.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($exceptions) -->
    <table class="title">
        <tr><th colspan="2" class="title">__Exceptions__: __summary__</th></tr>
        <!-- FOREACH $exceptions -->
            <tr>
                <td class="name"><a href="$exceptions.path">$exceptions.name</a></td>
                <td class="description">$exceptions.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($overView) -->
    <h1>__Namespace__ $namespace: __Description__</h1>
    <div class="comment" id="overview_description">$overView</div>
<!-- END -->
