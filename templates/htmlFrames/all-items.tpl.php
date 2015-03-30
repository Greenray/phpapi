<body id="frame">
<h1>[__Полный список]</h1>
[if=class]
    <h2>[__Классы]</h2>
    <ul>
    [each=class]
        <li><a href="{class[path]}" title="{class[package]}" target="main">{class[package]}\{class[name]}</a></li>
    [/each.class]
    </ul>
[/if.class]
[if=function]
    <h2>[__Функции]</h2>
    <ul>
    [each=function]
        <li><a href="{function[path]}" title="{function[package]}" target="main">{function[name]}</a></li>
    [/each.function]
    </ul>
[/if.function]
[if=global]
    <h2>[__Глобальные элементы]</h2>
    <ul>
    [each=global]
        <li><a href="{global[path]}" title="{global[package]}" target="main">{global[name]}</a></li>
    [/each.global]
    </ul>
[/if.global]
</body>
