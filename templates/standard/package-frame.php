<body id="frame">
<h1><a href="package-summary.html" target="main">{name}</a></h1>
[if=classes]
    <h2>[__Классы]</h2>
    <ul>
    [each=classes]<li><a href="{classes[path]}" target="main">{classes[name]}</a></li>[endeach.classes]
    </ul>
[endif.classes]
[if=interfaces]
    <h2>[__Интерфейсы]</h2>
    <ul>
    [each=interfaces]<li><a href="{interfaces[path]}" target="main">{interfaces[name]}</a></li>[endeach.interfaces]
    </ul>
[endif.interfaces]
[if=traits]
    <h2>[__Типажи]</h2>
    <ul>
    [each=traits]<li><a href="{traits[path]}" target="main">{traits[name]}</a></li>[endeach.traits]
    </ul>
[endif.traits]
[if=exceptions]
    <h2>[__Исключения]</h2>
    <ul>
    [each=exceptions]<li><a href="{exceptions[path]}" target="main">{exceptions[name]}</a></li>[endeach.exceptions]
    </ul>
[endif.exceptions]
[if=functions]
    <h2>[__Функции]</h2>
    <ul>
    [each=functions]<li><a href="{functions[path]}" target="main">{functions[name]}</a></li>[endeach.functions]
    </ul>
[endif.functions]
[if=globals]
    <h2>[__Глобальные элементы]</h2>
    <ul>
    [each=globals]<li><a href="{globals[path]}" target="main">{globals[name]}</a></li>[endeach.globals]
    </ul>
[endif.globals]
</body>
