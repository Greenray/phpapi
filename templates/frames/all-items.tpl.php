<body id="frame">
<h1>[__Полный список]</h1>
[if=class]
    <h2>[__Классы]</h2>
    <ul>
    [each=class]
        <li>
            <a href="{class[packpath]}package-summary.html" title="{class[packname]}" target="main">{class[packname]}</a> \
            <a href="{class[clpath]}" title="{class[name]}" target="main">{class[name]}</a>
        </li>
    [/each.class]
    </ul>
[/if.class]
[if=interface]
    <h2>[__Итерфейсы]</h2>
    <ul>
    [each=interface]
        <li>
            <a href="{interface[packpath]}package-summary.html" title="{interface[packname]}" target="main">{interface[packname]}</a> \
            <a href="{interface[intpath]}" title="{interface[name]}" target="main">{interface[name]}</a>
        </li>
    [/each.interface]
    </ul>
[/if.interface]
[if=trait]
    <h2>[__Трейты]</h2>
    <ul>
    [each=trait]
        <li>
            <a href="{trait[packpath]}package-summary.html" title="{trait[packname]}" target="main">{trait[packname]}</a> \
            <a href="{trait[trpath]}" title="{trait[name]}" target="main">{trait[name]}</a>
        </li>
    [/each.trait]
    </ul>
[/if.trait]
[if=exception]
    <h2>[__Исключения]</h2>
    <ul>
    [each=exception]
        <li>
            <a href="{exception[packpath]}package-summary.html" title="{exception[packname]}" target="main">{exception[packname]}</a> \
            <a href="{exception[expath]}" title="{exception[name]}" target="main">{exception[name]}</a>
        </li>
    [/each.exception]
    </ul>
[/if.exception]
[if=function]
    <h2>[__Функции]</h2>
    <ul>
    [each=function]
        <li>
            <a href="{function[packpath]}package-summary.html" title="{function[packname]}" target="main">{function[packname]}</a> \
            <a href="{function[funpath]}" title="{function[name]}" target="main">{function[name]}</a>
        </li>
    [/each.function]
    </ul>
[/if.function]
[if=global]
    <h2>[__Глобальные элементы]</h2>
    <ul>
    [each=global]
        <li><a href="{global[glpath]}" title="{global[name]}" target="main">{global[name]}</a></li>
    [/each.global]
    </ul>
[/if.global]
</body>
