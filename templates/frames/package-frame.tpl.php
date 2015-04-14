<body id="frame">
<h1><a href="package-summary.html" target="main">{name}</a></h1>
[if=class]
    <h2>[__Классы]</h2>
    <ul>
    [each=class]
        <li><a href="{class[frpath]}" target="main">{class[name]}</a></li>
    [/each.class]
    </ul>
[/if.class]
[if=interface]
    <h2>[__Интерфейсы]</h2>
    <ul>
    [each=interface]
        <li><a href="{interface[frpath]}" target="main">{interface[name]}</a></li>
    [/each.interface]
    </ul>
[/if.interface]
[if=trait]
    <h2>[__Трейты]</h2>
    <ul>
    [each=trait]
        <li><a href="{trait[frpath]}" target="main">{trait[name]}</a></li>
    [/each.trait]
    </ul>
[/if.trait]
[if=exception]
    <h2>[__Исключения]</h2>
    <ul>
    [each=exception]
        <li><a href="{exception[frpath]}" target="main">{exception[name]}</a></li>
    [/each.exception]
    </ul>
[/if.exception]
[if=function]
    <h2>[__Функции]</h2>
    <ul>
    [each=function]
        <li><a href="{function[frpath]}" target="main">{function[name]}</a></li>
    [/each.function]
    </ul>
[/if.function]
[if=global]
    <h2>[__Глобальные элементы]</h2>
    <ul>
    [each=global]
        <li><a href="{global[frpath]}" target="main">{global[name]}</a></li>
    [/each.global]
    </ul>
[/if.global]
</body>
