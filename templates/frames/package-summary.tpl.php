<hr />
<h1>[__Пространство имен] {namespace}</h1>
[if=shortView]
    <div class="comment">{shortView}</div>
    <dl><dt>[__Смотри]:</dt><dd><b><a href="#overview_description">[__Описание]</a></b></dd></dl>
[/if.shortView]
[if=class]
    <table class="title">
        <tr><th colspan="2" class="title">[__Классы]: [__общий обзор]</th></tr>
        [each=class]
            <tr>
                <td class="name"><a href="{class[path]}">{class[name]}</a></td>
                <td class="description">{class[desc]}</td>
            </tr>
        [/each.class]
    </table>
[/if.class]
[if=interface]
    <table class="title">
        <tr><th colspan="2" class="title">[__Интерфейсы]: [__общий обзор]</th></tr>
        [each=interface]
            <tr>
                <td class="name"><a href="{interface[path]}">{interface[name]}</a></td>
                <td class="description">{interface[desc]}</td>
            </tr>
        [/each.interface]
    </table>
[/if.interface]
[if=trait]
    <table class="title">
        <tr><th colspan="2" class="title">[__Трейты]: [__общий обзор]</th></tr>
        [each=trait]
            <tr>
                <td class="name"><a href="{trait[path]}">{trait[name]}</a></td>
                <td class="description">{trait[desc]}</td>
            </tr>
        [/each.trait]
    </table>
[/if.trait]
[if=exception]
    <table class="title">
        <tr><th colspan="2" class="title">[__Исключения]: [__общий обзор]</th></tr>
        [each=exception]
            <tr>
                <td class="name"><a href="{exception[path]}">{exception[name]}</a></td>
                <td class="description">{exception[desc]}</td>
            </tr>
        [/each.exception]
    </table>
[/if.exception]
[if=function]
    <table class="title">
        <tr><th colspan="2" class="title">[__Функции]: [__общий обзор]</th></tr>
        [each=function]
            <tr>
                <td class="name"><a href="{function[path]}">{function[name]}</a></td>
                <td class="description">{function[desc]}</td>
            </tr>
        [/each.function]
    </table>
[/if.function]
[if=global]
    <table class="title">
        <tr><th colspan="2" class="title">[__Глобальные элементы]: [__общий обзор]</th></tr>
        [each=global]
            <tr>
                <td class="name"><a href="{global[path]}">{global[name]}</a></td>
                <td class="description">{global[desc]}</td>
            </tr>
        [/each.global]
    </table>
[/if.global]
[if=overview]
    <h1>[__Пространство имен] {namespace}: [__Описание]</h1>
    <div class="comment" id="overview_description">{overview}</div>
[/if.overview]
