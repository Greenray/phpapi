<hr>
<h1>[__Пространство имен] {name}</h1>
[if=shortOverview]
    <div class="comment">{shortOverview}</div>
    <dl><dt>[__Смотри]:</dt><dd><b><a href="#overview_description">[__Описание]</a></b></dd></dl>
[endif.shortOverview]
[if=classes]
    <table class="title">
        <tr><th colspan="2" class="title">[__Класс: общий обзор]</th></tr>
        [each=classes]
            <tr>
                <td class="name"><a href="{classes[path]}">{classes[name]}</a></td>
                <td class="description">{classes[description]}</td>
            </tr>
        [endeach.classes]
    </table>
[endif.classes]
[if=interfaces]
    <table class="title">
        <tr><th colspan="2" class="title">[__Интерфейс: общий обзор]</th></tr>
        [each=interfaces]
            <tr>
                <td class="name"><a href="{interfaces[path]}">{interfaces[name]}</a></td>
                <td class="description">{interfaces[description]}</td>
            </tr>
        [endeach.interfaces]
    </table>
[endif.interfaces]
[if=traits]
    <table class="title">
        <tr><th colspan="2" class="title">[__Типажи: общий обзор]</th></tr>
        [each=traits]
            <tr>
                <td class="name"><a href="{traits[path]}">{traits[name]}</a></td>
                <td class="description">{traits[description]}</td>
            </tr>
        [endeach.traits]
    </table>
[endif.traits]
[if=exceptions]
    <table class="title">
        <tr><th colspan="2" class="title">[__Исключения: общий обзор]</th></tr>
        [each=exceptions]
            <tr>
                <td class="name"><a href="{exceptions[path]}">{exceptions[name]}</a></td>
                <td class="description">{exceptions[description]}</td>
            </tr>
        [endeach.exceptions]
    </table>
[endif.exceptions]
[if=functions]
    <table class="title">
        <tr><th colspan="2" class="title">[__Функции: общий обзор]</th></tr>
        [each=functions]
            <tr>
                <td class="name"><a href="{functions[path]}">{functions[name]}</a></td>
                <td class="description">{functions[description]}</td>
            </tr>
        [endeach.functions]
    </table>
[endif.functions]
[if=globals]
    <table class="title">
        <tr><th colspan="2" class="title">[__Глобальные элементы: общий обзор]</th></tr>
        [each=globals]
            <tr>
                <td class="name"><a href="{globals[path]}">{globals[name]}</a></td>
                <td class="description">{globals[description]}</td>
            </tr>
        [endeach.globals]
    </table>
[endif.globals]
[if=overview]
    <h1>[__Пространство имен] {name}: [__Описание]</h1>
    <div class="comment" id="overview_description">{ovrview}</div>
[endif.overview]
