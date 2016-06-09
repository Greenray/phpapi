<body id="frame">
    <h1><a href="package-summary.html" target="main">$package</a></h1>
    <!-- IF !empty($interfaces) -->
        <h2>__Interfaces__</h2>
        <ul>
        <!-- FOREACH $interfaces -->
            <li><a href="$interfaces.allpath" target="main">$interfaces.name</a></li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($traits) -->
        <h2>__Traits__</h2>
        <ul>
        <!-- FOREACH $traits -->
            <li><a href="$traits.allpath" target="main">$traits.name</a></li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($classes) -->
        <h2>__Classe__</h2>
        <ul>
            <!-- FOREACH $classes -->
                <li><a href="$classes.allpath" target="main">$classes.name</a></li>
            <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($functions) -->
        <h2>__Functions__</h2>
        <ul>
        <!-- FOREACH $functions -->
            <li><a href="$functions.allpath" target="main">$functions.name</a></li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($globals) -->
        <h2>__Globals__</h2>
        <ul>
        <!-- FOREACH $globals -->
            <li><a href="$globals.allpath" target="main">$globals.name</a></li>
        <!-- END -->
        </ul>
    <!-- END -->
    <!-- IF !empty($exceptions) -->
        <h2>__Exceptions__</h2>
        <ul>
        <!-- FOREACH $exceptions -->
            <li><a href="$exceptions.allpath" target="main">$exceptions.name</a></li>
        <!-- END -->
        </ul>
    <!-- END -->
</body>
