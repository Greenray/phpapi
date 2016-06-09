<body id="frame">
    <h1>__Полный список__</h1>
    <!-- IF !empty($interfaces) -->
        <h2>__Interfaces__</h2>
        <ul>
        <!-- FOREACH $interfaces -->
            <li>
                <a href="[$interfaces.packpath]package-summary.html" title="$interfaces.packname" target="main">$interfaces.packname</a> \
                <a href="$interfaces.path" title="$interfaces.name" target="main">$interfaces.name</a>
            </li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($traits) -->
        <h2>__Traits__</h2>
        <ul>
        <!-- FOREACH $traits -->
            <li>
                <a href="[$traits.packpath]package-summary.html" title="$traits.packname" target="main">$traits.packname</a> \
                <a href="$traits.path" title="$traits.name" target="main">$traits.name</a>
            </li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($classes) -->
        <h2>__Classes__</h2>
        <ul>
        <!-- FOREACH $classes -->
            <li>
                <a href="[$classes.packpath]package-summary.html" title="$classes.packname" target="main">$classes.packname</a> \
                <a href="$classes.path" title="$classes.name" target="main">$classes.name</a>
            </li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($functions) -->
        <h2>__Functions__</h2>
        <ul>
        <!-- FOREACH $functions -->
            <li>
                <a href="[$functions.packpath]package-summary.html" title="$functions.packname" target="main">$functions.packname</a> \
                <a href="$functions.path" title="$functions.name" target="main">$functions.name</a>
            </li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($globals) -->
        <h2>__Globals__</h2>
        <ul>
        <!-- FOREACH $globals -->
            <li><a href="$globals.path" title="$globals.name" target="main">$globals.name</a></li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($exceptions) -->
        <h2>__Exceptions__</h2>
        <ul>
        <!-- FOREACH $exceptions -->
            <li>
                <a href="[$exceptions.packpath]package-summary.html" title="$exceptions.packname" target="main">$exceptions.packname</a> \
                <a href="$exceptions.path" title="$exceptions.name" target="main">$exceptions.name</a>
            </li>
        <!-- END -->
        </ul>
    <!-- END -->
</body>
