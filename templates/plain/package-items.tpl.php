<h1>[__Пространства имен]</h1>
<ul>
[each=package]
    <li><a href="{package[path]}package-summary.html" target="_self">{package[name]}</a></li>
[/each.package]
</ul>
<h1>[__Пространство имен] {current}</h1>
[if=class]
    <h2>[__Классы]</h2>
    <ul>
    [each=class]
        <li><a href="{class[path]}" title="{class[package]}\{class[name]}" target="_self">{class[name]}</a></li>
    [/each.class]
    </ul>
[/if.class]
[if=interface]
    <h2>[__Итерфейсы]</h2>
    <ul>
    [each=interface]
        <li><a href="{interface[path]}" title="{interface[package]}\{interface[name]}" target="_self">{interface[name]}</a></li>
    [/each.interface]
    </ul>
[/if.interface]
[if=trait]
    <h2>[__Трейты]</h2>
    <ul>
    [each=trait]
        <li><a href="{trait[path]}" title="{trait[package]}\{trait[name]}" target="_self">{trait[name]}</a></li>
    [/each.trait]
    </ul>
[/if.trait]
[if=exception]
    <h2>[__Исключения]</h2>
    <ul>
    [each=exception]
        <li><a href="{exception[path]}" title="{exception[package]}" target="_self">{exception[name]}</a></li>
    [/each.exception]
    </ul>
[/if.exception]
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
