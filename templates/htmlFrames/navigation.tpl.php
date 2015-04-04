<div class="header">
    <span style="float:right">{header}</span>
    [if=section]
        <ul>
        [each=section]
            <li[if=section[selected]] class="active"[/if.section]>{section[title]}</li>
        [/each.section]
        </ul>
    [/if.section]
</div>
<div class="small_links">[__Фреймы]: <a href="{path}index.html" target="_top"> [__Включить]</a> | <a href="{path}{file}" target="_top"> [__Выключить]</a></div>
[if=class]
    <div class="small_links">
        [__Общий обзор]: <a href="#summary_fields">[__Поля]</a> | <a href="#summary_methods">[__Методы]</a> | <a href="#summary_constructor">[__Конструктор]</a>
        [__Детали]: <a href="#details_fields">[__Поля]</a> | <a href="#details_methods">[__Методы]</a> | <a href="#details_constructor">[__Конструктор]</a>
    </div>
[/if.class]
[if=function]
    <div class="small_links">
        [__Общий обзор]: <a href="#summary_functions">[__Функции]</a>
        [__Детали]: <a href="#details_functions">[__Функции]</a>
    </div>
[/if.function]
[if=global]
    <div class="small_links">
        [__Общий обзор]: <a href="#summary_globals">[__Глобальные элементы]</a>
        [__Детали]: <a href="#details_globals">[__Глобальные элементы]</a>
    </div>
[/if.global]
