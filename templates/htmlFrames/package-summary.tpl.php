<hr>
<h1>[__Пространство имен] {namespace}</h1>
[if=shortOverview]
    <div class="comment">{shortOverview}</div>
    <dl><dt>[__Смотри]:</dt><dd><b><a href="#overview_description">[__Описание]</a></b></dd></dl>
[/if.shortOverview]
[if=class]
    <table class="title">
        <tr><th colspan="2" class="title">[__Классы: общий обзор]</th></tr>
        [each=class]
            <tr>
                <td class="name"><a href="{class[path]}">{class[name]}</a></td>
                [if=description]<td class="description">{class[description]}</td>[/if.description]
            </tr>
        [/each.class]
    </table>
[/if.class]
[if=interface]
    <table class="title">
        <tr><th colspan="2" class="title">[__Интерфейсы: общий обзор]</th></tr>
        [each=interface]
            <tr>
                <td class="name"><a href="{interface[path]}">{interface[name]}</a></td>
                [if=description]<td class="description">{interface[description]}</td>[/if.description]
            </tr>
        [/each.interface]
    </table>
[/if.interface]
[if=trait]
    <table class="title">
        <tr><th colspan="2" class="title">[__Трейты: общий обзор]</th></tr>
        [each=trait]
            <tr>
                <td class="name"><a href="{trait[path]}">{trait[name]}</a></td>
                [if=description]<td class="description">{trait[description]}</td>
            </tr>
        [/each.trait]
    </table>
[/if.trait]
[if=exception]
    <table class="title">
        <tr><th colspan="2" class="title">[__Исключения: общий обзор]</th></tr>
        [each=exception]
            <tr>
                <td class="name"><a href="{exception[path]}">{exception[name]}</a></td>
                [if=description]<td class="description">{exception[description]}</td>[/if.description]
            </tr>
        [/each.exception]
    </table>
[/if.exception]
[if=function]
    <table class="title">
        <tr><th colspan="2" class="title">[__Функции: общий обзор]</th></tr>
        [each=function]
            <tr>
                <td class="name"><a href="{function[path]}">{function[name]}</a></td>
                [if=description]<td class="description">{function[description]}</td>[/if.description]
            </tr>
        [/each.function]
    </table>
[/if.function]
[if=global]
    <table class="title">
        <tr><th colspan="2" class="title">[__Глобальные элементы: общий обзор]</th></tr>
        [each=global]
            <tr>
                <td class="name"><a href="{global[path]}">{global[name]}</a></td>
                [if=description]<td class="description">{global[description]}</td>[/if.description]
            </tr>
        [/each.global]
    </table>
[/if.global]
[if=overview]
    <h1>[__Пространство имен] {name}: [__Описание]</h1>
    <div class="comment" id="overview_description">{overview}</div>
[/if.overview]
