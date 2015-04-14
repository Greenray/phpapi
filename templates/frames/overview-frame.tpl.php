<body id="frame">
    <h1>{header}</h1>
    <ul><li><a href="all-items.html" target="index">[__Полный список]</a></li></ul>
    <h1>[__Пространства имен]</h1>
    <ul>
    [each=package]
        <li><a href="{package[path]}package-frame.html" target="index">{package[name]}</a></li>
    [/each.package]
    </ul>
</body>