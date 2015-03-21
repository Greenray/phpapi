<hr>
<h1>[__Устаревшие элементы]</h1>
<hr>
[if=menu]
    <ul>
        [each=menu]
            [if=menu[classes]]   <li><a href="#deprecated_classes">[__Классы]</a></li>[endif.menu]
            [if=menu[fields]]    <li><a href="#deprecated_fields">[__Поля]</a></li>[endif.menu]
            [if=menu[methods]]   <li><a href="#deprecated_methods">[__Методы]</a></li>[endif.menu]
            [if=menu[globals]]   <li><a href="#deprecated_globals">[__Глобальгые элементы]</a></li>[endif.menu]
            [if=menu[functions]] <li><a href="#deprecated_functions">[__Функции]</a></li>[endif.menu]
        [endeach.menu]
    </ul>
[endif.menu]
[if=classes]
    <table id="deprecated_classes">
        <tr><th colspan="2" class="title">[__Классы]</th></tr>
        [each=classes]
            <tr>
                <td class="name"><a href="{classes[path]}">{classes[name]}</a></td>
                <td class="description">{classes[desc]}</td>
            </tr>
        [endeach.classes]
    </table>
[endif.classes]
[if=fields]
    <table id="deprecated_fields">
        <tr><th colspan="2" class="title">[__Поля]</th></tr>
        [each=fields]
            <tr>
                <td class="name"><a href="{fields[path]}">{fields[name]}</a></td>
                <td class="description">{fields[desc]}</td>
            </tr>
        [endeach.fields]
    </table>
[endif.fields]
[if=methods]
    <table id="deprecated_methods">
        <tr><th colspan="2" class="title">[__Методы]</th></tr>
        [each=methods]
            <tr>
                <td class="name"><a href="{methods[path]}">{methods[name]}</a></td>
                <td class="description">{methods[desc]}</td>
            </tr>
        [endeach.methods]
    </table>
[endif.methods]
[if=globals]
    <table id="deprecated_globals">
        <tr><th colspan="2" class="title">[__Глобальные элементы]</th></tr>
        [each=globals]
            <tr>
                <td class="name"><a href="{globals[path]}">{globals[name]}</a></td>
                <td class="description">{globals[desc]}</td>
            </tr>
        [endeach.globals]
    </table>
[endif.globals]
[if=functions]
    <table id="deprecated_functions"">
        <tr><th colspan="2" class="title">[__Функции]</th></tr>
        [each=functions]
            <tr>
                <td class="name"><a href="{functions[path]}">{functions[name]}</a></td>
                <td class="description">{functions[desc]}</td>
            </tr>
        [endeach.functions]
    </table>
[endif.functions]