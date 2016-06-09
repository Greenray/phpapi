<h1>__Namespaces__</h1>
<ul>
<!-- FOREACH $packages -->
    <li><a href="[$packages.path]package-summary.html">$packages.name</a></li>
<!-- END -->
</ul>
<!-- IF !empty($interfaces) -->
    <h2>__Interfaces__</h2>
    <ul>
    <!-- FOREACH $interfaces -->
        <li>
            <a href="[$interfaces.packpath]package-summary.html" title="$interfaces.packname">$interfaces.packname</a> \
            <a href="$interfaces.path" title="$interfaces.name">$interfaces.name</a>
        </li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($traits) -->
    <h2>__Traits__</h2>
    <ul>
    <!-- FOREACH $traits -->
        <li>
            <a href="[$traits.packpath]package-summary.html" title="$traits.packname">$traits.packname</a> \
            <a href="$traits.path" title="$traits.name">$traits.name</a>
        </li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($classes) -->
    <h2>__Classes__</h2>
    <ul>
    <!-- FOREACH $classes -->
        <li>
            <a href="[$classes.packpath]package-summary.html" title="$classes.packname">$classes.packname</a> \
            <a href="$classes.path" title="$classes.name">$classes.name</a>
        </li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($functions) -->
    <h2>__Functions__</h2>
    <ul>
    <!-- FOREACH $functions -->
        <li>
            <a href="[$functions.packpath]package-summary.html" title="$functions.packname">$functions.packname</a> \
            <a href="$functions.path" title="$functions.name">$functions.name</a>
        </li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($globals) -->
    <h2>__Globals__</h2>
    <ul>
    <!-- FOREACH $globals -->
        <li><a href="$globals.path" title="$globals.name">$globals.name</a></li>
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($exceptions) -->
    <h2>__Exceptions__</h2>
    <ul>
    <!-- FOREACH $exceptions -->
        <li>
            <a href="[$exceptions.packpath]package-summary.html" title="$exceptions.packname">$exceptions.packname</a> \
            <a href="$exceptions.path" title="$exceptions.name">$exceptions.name</a>
        </li>
    <!-- END -->
    </ul>
<!-- END -->
