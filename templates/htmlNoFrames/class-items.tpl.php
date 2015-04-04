<h1>[__Пространство имен] {package}</h1>
[if=class]
    <h2>[__Классы]</h2>
    <ul>
    [each=class]
        <li><a href="{class[path]}" title="{class[package]}\{class[name]}" target="_self">{class[name]}</a></li>
    [/each.class]
    </ul>
[/if.class]
[if=function]
    <h2>[__Функции]</h2>
    <ul>
    [each=function]
        <li><a href="{function[path]}" title="{function[package]}" target="_self">{function[name]}</a></li>
    [/each.function]
    </ul>
[/if.function]
[if=global]
    <h2>[__Глобальные элементы]</h2>
    <ul>
    [each=global]
        <li><a href="{global[path]}" title="{global[package]}" target="_self">{global[name]}</a></li>
    [/each.global]
    </ul>
[/if.global]
