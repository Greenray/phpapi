<body id="frame">
<h1>[__Полный список]</h1>
[if=classes]
    <h2>[__Классы]</h2>
    <ul>
    [each=classes]<li><a href="{classes[path]}" title="{classes[package]}" target="main">{classes[name]}</a></li>[endeach.classes]
    </ul>
[endif.classes]
[if=functions]
    <h2>[__Функции]</h2>
    <ul>
    [each=functions]<li><a href="{functions[path]}package-functions.html#{functions[name]}" title="{functions[package]}" target="main">{functions[name]}</a></li>[endeach.functions]
    </ul>
[endif.functions]
[if=globals]
    <h2>[__Глобальные элементы]</h2>
    <ul>
    [each=globals]<li><a href="{globals[path]}package-globals.html#{globals[name]}" title="{globals[package]}" target="main">{globals[name]}</a></li>[endeach.globals]
    </ul>
[endif.globals]
</body>
