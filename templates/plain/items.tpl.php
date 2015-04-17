<h1>[__Пространства имен]</h1>
<ul>
[each=package]
    <li><a href="{package[path]}package-summary.html" target="_self">{package[name]}</a></li>
[/each.package]
</ul>
[if=class]
    <h2>[__Классы]</h2>
    <ul>
    [each=class]
        <li>
            <a href="{class[packpath]}package-summary.html" title="{class[packname]}" target="_self">{class[packname]}</a> \
            <a href="{class[path]}" title="{class[name]}" target="_self">{class[name]}</a>
        </li>
    [/each.class]
    </ul>
[/if.class]
[if=interface]
    <h2>[__Итерфейсы]</h2>
    <ul>
    [each=interface]
        <li>
            <a href="{interface[packpath]}package-summary.html" title="{interface[packname]}" target="_self">{interface[packname]}</a> \
            <a href="{interface[path]}" title="{interface[name]}" target="_self">{interface[name]}</a>
        </li>
    [/each.interface]
    </ul>
[/if.interface]
[if=trait]
    <h2>[__Трейты]</h2>
    <ul>
    [each=trait]
        <li>
            <a href="{trait[packpath]}package-summary.html" title="{trait[packname]}" target="_self">{trait[packname]}</a> \
            <a href="{trait[path]}" title="{trait[name]}" target="_self">{trait[name]}</a>
        </li>
    [/each.trait]
    </ul>
[/if.trait]
[if=exception]
    <h2>[__Исключения]</h2>
    <ul>
    [each=exception]
        <li>
            <a href="{exception[packpath]}package-summary.html" title="{exception[packname]}" target="_self">{exception[packname]}</a> \
            <a href="{exception[path]}" title="{exception[name]}" target="_self">{exception[name]}</a>
        </li>
    [/each.exception]
    </ul>
[/if.exception]
[if=function]
    <h2>[__Функции]</h2>
    <ul>
    [each=function]
        <li>
            <a href="{function[packpath]}package-summary.html" title="{function[packname]}" target="_self">{function[packname]}</a> \
            <a href="{function[path]}" title="{function[name]}" target="_self">{function[name]}</a>
        </li>
    [/each.function]
    </ul>
[/if.function]
[if=global]
    <h2>[__Глобальные элементы]</h2>
    <ul>
    [each=global]
        <li><a href="{global[path]}" title="{global[name]}" target="_self">{global[name]}</a></li>
    [/each.global]
    </ul>
[/if.global]
