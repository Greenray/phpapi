<body id="frame">
    <h1>$header</h1>
    <ul><li><a href="all-items.html" target="index">[__All items]</a></li></ul>
    <h1>[__Namespaces]</h1>
    <ul>
    <!-- FOREACH package = $packages -->
        <li><a href="{$package.path:}package-frame.html" target="index">$package.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
</body>
