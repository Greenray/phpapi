<hr>
<h1>[__Устаревшие элементы]</h1>
<hr>
[if=menu]
    <ul>
        [each=menu]
            [if=menu[class]]    <li><a href="#deprecated_classes">[__Классы]</a></li>[/if.menu]
            [if=menu[field]]    <li><a href="#deprecated_fields">[__Поля]</a></li>[/if.menu]
            [if=menu[method]]   <li><a href="#deprecated_methods">[__Методы]</a></li>[/if.menu]
            [if=menu[global]]   <li><a href="#deprecated_globals">[__Глобальгые элементы]</a></li>[/if.menu]
            [if=menu[function]] <li><a href="#deprecated_functions">[__Функции]</a></li>[/if.menu]
        [/each.menu]
    </ul>
[/if.menu]
[if=class]
    <table id="deprecated_classes">
        <tr><th colspan="2" class="title">[__Классы]</th></tr>
        [each=class]
            <tr>
                <td class="name"><a href="{class[path]}">{class[name]}</a></td>
                <td class="description">{class[desc]}</td>
            </tr>
        [/each.class]
    </table>
[/if.class]
[if=field]
    <table id="deprecated_fields">
        <tr><th colspan="2" class="title">[__Поля]</th></tr>
        [each=field]
            <tr>
                <td class="name"><a href="{field[path]}">{field[name]}</a></td>
                <td class="description">{field[desc]}</td>
            </tr>
        [/each.field]
    </table>
[/if.field]
[if=method]
    <table id="deprecated_methods">
        <tr><th colspan="2" class="title">[__Методы]</th></tr>
        [each=method]
            <tr>
                <td class="name"><a href="{method[path]}">{method[name]}</a></td>
                <td class="description">{method[desc]}</td>
            </tr>
        [/each.method]
    </table>
[/if.method]
[if=function]
    <table id="deprecated_functions"">
        <tr><th colspan="2" class="title">[__Функции]</th></tr>
        [each=function]
            <tr>
                <td class="name"><a href="{function[path]}">{function[name]}</a></td>
                <td class="description">{function[desc]}</td>
            </tr>
        [/each.function]
    </table>
[/if.function]
[if=global]
    <table id="deprecated_globals">
        <tr><th colspan="2" class="title">[__Глобальные элементы]</th></tr>
        [each=global]
            <tr>
                <td class="name"><a href="{global[path]}">{global[name]}</a></td>
                <td class="description">{global[desc]}</td>
            </tr>
        [/each.global]
    </table>
[/if.global]
