<hr>
<div class="package">[__Пространство имен] {package}</div>
<div class="location">{location}</div>
<h1>{qualified}</h1>
<div id="list">
    <ul>
        {tree}
    </ul>
</div>
[if=implements]
    <dl>
        <dt>Interfaces:</dt>
        <dd>{implements}</dd>
    </dl>
[/if.implements]
[if=traits]
    <dl>
        <dt>Traits:</dt>
        <dd>{traits}</dd>
    </dl>
[/if.traits]
[if=subclasses]
    <dl>
        <dt>Subclasses:</dt>
        <dd>{subclasses}</dd>
    </dl>
[/if.subclasses]
<hr>
<p class="signature">{ismodifiers} {is} <strong>{isname}</strong>[if=extends]{extends}[/if.extends]</p>
<div class="comment" id="overview_description">{textag}</div>
[if=main_tags]{main_tags}[/if.main_tags]
[if=constant]
    <table id="summary_constants">
        <tr><th colspan="2">[__Константы: общий обзор]</th></tr>
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
        <tr><th colspan="2">[__Поля: общий обзор]</th></tr>
        [each=field]
            <tr>
                <td class="type w_200">{field[modifiers]} {field[type]}</td>
                <td class="description">
                    <pre><a href="#{field[name]}"><span class="green">${field[name]}</span></a>[if=field[value]]{field[value]}[/if.field]</pre>
                    <p class="description">{field[shortDesc]}</p>
                </td>
            </tr>
        [/each.field]
    </table>
[/if.field]
[if=inheritFields]
    [each=inheritFields]
        <table class="inherit">
            <tr><th colspan="2">[__Поля, унаследованные из] {inheritFields[qualifiedName]}</th></tr>
            <tr><td><a href="{inheritFields[path]}">{inheritFields[name]}</a></td></tr>
        </table>
    [/each.inheritFields]
[/if.inheritFields]
[if=constructor]
    <table id="summary_constructor">
        <tr><th colspan="2">[__Конструктор: общий обзор]</th></tr>
        <tr>
            <td class="type w_200">{modifiers} {type}</td>
            <td class="description">
                <p><a href="#{name}"><strong><span class="black">{name}</span></strong></a>{signature}</p>
                <p class="description">{shortDesc}</p>
            </td>
        </tr>
    </table>
[/if.constructor]
[if=destructor]
    <table id="summary_destructor">
        <tr><th colspan="2">[__Деструктор: общий обзор]</th></tr>
        <tr>
            <td class="type w_200">{modifiers} {type}</td>
            <td class="description">
                <p><a href="#{name}"><strong><span class="black">{name}</span></strong></a>{signature}</p>
                <p class="description">{shortDesc}</p>
            </td>
        </tr>
    </table>
[/if.destructor]
[if=method]
    <table id="summary_methods">
        <tr><th colspan="2">[__Методы: общий обзор]</th></tr>
        [each=method]
            <tr>
                <td class="type w_200">{method[modifiers]} {method[type]}</td>
                <td class="description">
                    <p><a href="#{method[name]}"><strong><span class="black">{method[name]}</span></strong></a> {method[signature]}</p>
                    <p class="description">{method[shortDesc]}</p>
                </td>
            </tr>
        [/each.method]
    </table>
[/if.method]
[if=inheritMethods]
    [each=inheritMethods]
        <table class="inherit">
            <tr><th colspan="2">[__Методы, унаследованные из] {inheritMethods[qualifiedName]}</th></tr>
            <tr><td><a href="{inheritMethods[path]}">{inheritMethods[name]}</a></td></tr>
        </table>
    [/each.inheritMethods]
[/if.inheritMethods]
[if=constant]
    <h2 id="details_constants">[__Константы: детали]</h2>
    [each=constant]
        <div class="location">{constant[location]}</div>
        <pre class="signature" id="{constant[name]}">{constant[modifiers]} {constant[type]} <strong>{constant[name]}</strong>{constant[value]}</pre>
        <div class="details">{constant[fullDesc]}</div>
        <hr>
    [/each.constant]
[/if.constant]
[if=field]
    <h2 id="details_fields">[__Поля: детали]</h2>
    [each=field]
        <div class="location">{field[location]}</div>
        <pre class="signature" id="{field[name]}">{field[modifiers]}{field[type]} <strong><span class="green">${field[name]}</span></strong>{field[value]}</pre>
        <div class="details">{field[fullDesc]}</div>
        <hr>
    [/each.field]
[/if.field]
[if=constructor]
    <h2 id="details_constructor">[__Конструктор: детали]</h2>
    <div class="location">{location}</div>
    <code class="signature" id="{name}">{modifiers} {type} <strong>{name}</strong>{signature}</code>
    <div class="details">
        {fullDesc}
        {tags}
    </div>
[/if.constructor]
[if=destructor]
    <h2 id="details_destructor">[__Деструктор: детали]</h2>
    <div class="location">{location}</div>
    <code class="signature" id="{name}">{modifiers} {type} <strong>{name}</strong>{signature}</code>
    <div class="details">
        {fullDesc}
        {tags}
    </div>
[/if.destructor]
[if=method]
    <h2 id="details_methods">[__Методы: детали]</h2>
    [each=method]
        <div class="location">{method[location]}</div>
        <code class="signature" id="{method[name]}">{method[modifiers]} {method[type]} <strong>{method[name]}</strong> {method[signature]}</code>
        <div class="details">
            {method[fullDesc]}
            {method[tags]}
        </div>
        <hr>
    [/each.method]
[/if.method]
