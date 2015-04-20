<hr />
<div class="package">[__Пространство имен] {package}</div>
<h1>{qualified}</h1>
<div id="list">
    <ul>
        {tree}
    </ul>
</div>
[if=implements]
    <dl>
        <dt>[__Интерфесы]:</dt>
        [each=implements]<dd>{implements[name]}</dd>[/each.implements]
    </dl>
[/if.implements]
[if=trait]
    <dl>
        <dt>[__Трейты]:</dt>
        [each=trait]<dd>{trait[name]}</dd>[/each.trait]
    </dl>
[/if.trait]
[if=subclass]
    <dl>
        <dt>[__Подклассы]:</dt>
        [each=subclass]<dd>{subclass[name]}</dd>[/each.subclass]
    </dl>
[/if.subclass]
<hr />
<p class="arguments">{ismodifiers} {is} <strong>{isname}</strong>[if=extends]{extends}[/if.extends]</p>
<div class="comment" id="overview_description">{textTag}</div>
[if=mainParams]{mainParams}[/if.mainParams]
[if=constant]
    <table id="summary_constants">
        <tr><th colspan="2">[__Константы]: [__общий обзор]</th></tr>
        [each=constant]
            <tr>
                <td class="type w_200">{constant[modifiers]} {constant[type]}</td>
                <td class="description">
                    <pre><a href="#{constant[name]}"><span class="lilac">{constant[name]}</span></a>{constant[value]}</pre>
                    <p class="description">{constant[shortDesc]}</p>
                </td>
            </tr>
        [/each.constant]
    </table>
[/if.constant]
[if=field]
    <table id="summary_fields">
        <tr><th colspan="2">[__Поля]: [__общий обзор]</th></tr>
        [each=field]
            <tr>
                <td class="type w_200">{field[modifiers]} {field[type]}</td>
                <td class="description">
                    <pre><a href="#{field[name]}"><span class="green">{field[name]}</span></a>[if=field[value]]{field[value]}[/if.field]</pre>
                    <p class="description">{field[shortDesc]}</p>
                </td>
            </tr>
        [/each.field]
    </table>
[/if.field]
[if=inheritFields]
    [each=inheritFields]
        <table class="inherit">
            <tr><th colspan="2">[__Поля, унаследованные из] {inheritFields[fullNamespace]}</th></tr>
            <tr><td><a href="{inheritFields[path]}">{inheritFields[name]}</a></td></tr>
        </table>
    [/each.inheritFields]
[/if.inheritFields]
[if=constructor]
    <table id="summary_constructor">
        <tr><th colspan="2">[__Конструктор]: [__общий обзор]</th></tr>
        <tr>
            <td class="type w_200">{modifiers} {type}</td>
            <td class="description">
                <p><a href="#{name}"><strong><span class="black">{name}</span></strong></a>{arguments}</p>
                <p class="description">{shortDesc}</p>
            </td>
        </tr>
    </table>
[/if.constructor]
[if=destructor]
    <table id="summary_destructor">
        <tr><th colspan="2">[__Деструктор]: [__общий обзор]</th></tr>
        <tr>
            <td class="type w_200">{modifiers} {type}</td>
            <td class="description">
                <p><a href="#{name}"><strong><span class="black">{name}</span></strong></a>{arguments}</p>
                <p class="description">{shortDesc}</p>
            </td>
        </tr>
    </table>
[/if.destructor]
[if=method]
    <table id="summary_methods">
        <tr><th colspan="2">[__Методы]: [__общий обзор]</th></tr>
        [each=method]
            <tr>
                <td class="type w_200">{method[modifiers]} {method[type]}</td>
                <td class="description">
                    <p><a href="#{method[name]}"><strong><span class="black">{method[name]}</span></strong></a> {method[arguments]}</p>
                    <p class="description">{method[shortDesc]}</p>
                </td>
            </tr>
        [/each.method]
    </table>
[/if.method]
[if=inheritMethods]
    [each=inheritMethods]
        <table class="inherit">
            <tr><th colspan="2">[__Методы, унаследованные из] {inheritMethods[fullNamespace]}</th></tr>
            <tr><td><a href="{inheritMethods[path]}">{inheritMethods[name]}</a></td></tr>
        </table>
    [/each.inheritMethods]
[/if.inheritMethods]
[if=constant]
    <h2 id="details_constants">[__Константы]: [__детали]</h2>
    [each=constant]
        <div class="location">{constant[location]}</div>
        <pre class="arguments" id="{constant[name]}">{constant[modifiers]} {constant[type]} <strong>{constant[name]}</strong>{constant[value]}</pre>
        <div class="details">{constant[fullDesc]}</div>
        <hr />
    [/each.constant]
[/if.constant]
[if=field]
    <h2 id="details_fields">[__Поля]: [__детали]</h2>
    [each=field]
        <div class="location">{field[location]}</div>
        <pre class="arguments" id="{field[id]}">{field[modifiers]}{field[type]} <strong><span class="green">{field[name]}</span></strong>{field[value]}</pre>
        <div class="details">{field[fullDesc]}</div>
        <hr />
    [/each.field]
[/if.field]
[if=constructor]
    <h2 id="details_constructor">[__Конструктор]: [__детали]</h2>
    <div class="location">{location}</div>
    <code class="arguments" id="{name}">{modifiers} {type} <strong>{name}</strong>{arguments}</code>
    <div class="details">
        [foreach=include.k.file]
        {file}
        [/foreach.include]
        <p class="description">{fullDesc}</p>
        {parameters}
    </div>
[/if.constructor]
[if=destructor]
    <h2 id="details_destructor">[__Деструктор]: [__детали]</h2>
    <div class="location">{location}</div>
    <code class="arguments" id="{name}">{modifiers} {type} <strong>{name}</strong>{arguments}</code>
    <div class="details">
        {fullDesc}
        {parameters}
    </div>
[/if.destructor]
[if=method]
    <h2 id="details_methods">[__Методы]: [__детали]</h2>
    [each=method]
        <div class="location">{method[location]}</div>
        <code class="arguments" id="{method[name]}">{method[modifiers]} {method[type]} <strong>{method[name]}</strong> {method[arguments]}</code>
        <div class="details">
            [foreach=include.k.file]
            {file}
            [/foreach.include]
            <p class="description">{method[fullDesc]}</p>
            {method[parameters]}
        </div>
        <hr />
    [/each.method]
[/if.method]
