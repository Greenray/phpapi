<body id="frame">
    <h1>$header</h1>
    <ul>
        <li>
            <a href="all-items.html" target="index">__All items__</a>
        </li>
    </ul>
    <h1>__Namespaces__</h1>
    <ul>
    <!-- FOREACH $packages -->
        <li><a href="[$packages.path]package-frame.html" target="index">$packages.name</a></li>
    <!-- END -->
    </ul>
</body>
