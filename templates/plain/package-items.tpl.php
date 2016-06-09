<h1>__Namespaces__</h1>
<ul>
<!-- FOREACH $packages -->
    <li><a href="[$packages.path]package-summary.html">$packages.name</a></li>
<!-- END -->
</ul>
<h1>__Namespace__ $current</h1>
<!-- IF !empty($interfaces) -->
    <h2>__Interfaces__</h2>
    <ul>
    <!-- FOREACH $interfaces -->
        <li><a href="$interfaces.path" title="$interfaces.package \ $interfaces.name">$interfaces.name</a></li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($traits) -->
    <h2>__Traits__</h2>
    <ul>
    <!-- FOREACH $traits -->
        <li><a href="$traits.path" title="$traits.package \ $traits.name">$traits.name</a></li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($classes) -->
    <h2>__Classes__</h2>
    <ul>
    <!-- FOREACH $classes -->
        <li><a href="$classes.path" title="$classes.package \ $classes.name">$classes.name</a></li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($functions) -->
    <h2>__Functions__</h2>
    <ul>
    <!-- FOREACH $functions -->
        <li><a href="$functions.path" title="$functions.package">$functions.name</a></li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($globals) -->
    <h2>__Globals__</h2>
    <ul>
    <!-- FOREACH $globals -->
        <li><a href="$globals.path" title="$globals.package">$globals.name</a></li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($exceptions) -->
    <h2>__Exceptions__</h2>
    <ul>
    <!-- FOREACH $exceptions -->
        <li><a href="$exceptions.path" title="$exceptions.package">$exceptions.name</a></li>
    <!-- END -->
    </ul>
<!-- END -->
