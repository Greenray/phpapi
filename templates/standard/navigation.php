<div class="header">
    <span style="float:right">{header}</span>
    [if=sections]
        <ul>
            [each=sections]
                [ifelse=sections[selected]]
                    <li class="active">{sections[title]}</li>
                [else]
                    <li>{sections[title]}</li>
                [endelse]
            [endeach.sections]
        </ul>
    [endif.sections]
</div>
<div class="small_links">[__Фреймы]: <a href="{path}index.html" target="_top"> [__включить]</a> | <a href="{path}{file}" target="_top"> [__выключить]</a></div>
[if=class]
    <div class="small_links">
        [__Общий обзор]: <a href="#summary_fields">[__Поля]</a> | <a href="#summary_methods">[__Методы]</a> | <a href="#summary_constructor">[__Конструктор]</a>
        [__Детали]: <a href="#details_fields">[__Поля]</a> | <a href="#details_methods">[__Методы]</a> | <a href="#details_constructor">[__Конструктор]</a>
    </div>
[endif.class]
[if=function]
    <div class="small_links">
        [__Общий обзор]: <a href="#summary_functions">[__Функции]</a>
        [__Детали]: <a href="#details_functions">[__Функции]</a>
    </div>
[endif.function]
[if=global]
    <div class="small_links">
        [__Общий обзор]: <a href="#summary_globals">[__Глобальные элементы]</a>
        [__Детали]: <a href="#details_globals">[__Глобальные элементы]</a>
    </div>
[endif.global]
