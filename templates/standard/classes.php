<hr>
<div class="qualifiedName">{qualifiedName}</div>
<div class="location">{location}</div>
<h1>{qualified}</h1>
<pre class="tree">{tree}</pre>
[if=implements]
    <dl>
        <dt>Interfaces:</dt>
        <dd>{implements}</dd>
    </dl>
[endif.implements]
[if=traits]
    <dl>
        <dt>Traits:</dt>
        <dd>{traits}</dd>
    </dl>
[endif.traits]
[if=subclasses]
    <dl>
        <dt>Subclasses:</dt>
        <dd>{subclasses}</dd>
    </dl>
[endif.subclasses]
<hr>
<p class="signature">{ismodifiers} {is} <strong>{isname}</strong>[if=extends]{extends}[endif.extends]</p>
<div class="comment" id="overview_description">{textag}</div>
[if=main_tags]{main_tags}[endif.main_tags]
[if=constants]
    <table id="summary_constants">
        <tr><th colspan="2">[__Константы: общий обзор]</th></tr>
        [each=constants]
            <tr>
                <td class="type w_200">{constants[modifiers]} {constants[type]}</td>
                <td class="description">
                    <code><p><a href="#{constants[name]}"><span class="lilac">{constants[name]}</span></a>{constants[value]}</p></code>
                    <p class="description">{constants[shortDesc]}</p>
                </td>
            </tr>
        [endeach.constants]
    </table>
[endif.constants]
[if=fields]
    <table id="summary_fields">
        <tr><th colspan="2">[__Поля: общий обзор]</th></tr>
        [each=fields]
            <tr>
                <td class="type w_200">{fields[modifiers]} {fields[type]}</td>
                <td class="description">
                    <code><p><a href="#{fields[name]}"><span class="green">${fields[name]}</span></a>[if=fields[value]]{fields[value]}[endif.fields]</p></code>
                    <p class="description">{fields[shortDesc]}</p>
                </td>
            </tr>
        [endeach.fields]
    </table>
[endif.fields]
[if=inheritFields]
    [each=inheritFields]
        <table class="inherit">
            <tr><th colspan="2">[__Поля, унаследованные из] {inheritFields[qualifiedName]}</th></tr>
            <tr><td><a href="{inheritFields[path]}">{inheritFields[name]}</a></td></tr>
        </table>
    [endeach.inheritFields]
[endif.inheritFields]
[if=constructor]
    <table id="summary_constructor">
        <tr><th colspan="2">[__Конструктор: общий обзор]</th></tr>
        <tr>
            <td class="type w_200">{modifiers} {type}</td>
            <td class="description">
                <code><p><a href="#{name}"><strong><span class="black">{name}</span></strong></a>{signature}</p></code>
                <p class="description">{shortDesc}</p>
            </td>
        </tr>
    </table>
[endif.constructor]
[if=destructor]
    <table id="summary_destructor">
        <tr><th colspan="2">[__Деструктор: общий обзор]</th></tr>
        <tr>
            <td class="type w_200">{modifiers} {type}</td>
            <td class="description">
                <code><p><a href="#{name}"><strong><span class="black">{name}</span></strong></a>{signature}</p></code>
                <p class="description">{shortDesc}</p>
            </td>
        </tr>
    </table>
[endif.destructor]
[if=methods]
    <table id="summary_methods">
        <tr><th colspan="2">[__Методы: общий обзор]</th></tr>
        [each=methods]
            <tr>
                <td class="type w_200">{methods[modifiers]} {methods[type]}</td>
                <td class="description">
                    <code><p><a href="#{methods[name]}"><strong><span class="black">{methods[name]}</span></strong></a>{methods[signature]}</p></code>
                    <p class="description">{methods[shortDesc]}</p>
                </td>
            </tr>
        [endeach.methods]
    </table>
[endif.methods]
[if=inheritMethods]
    [each=inheritMethods]
        <table class="inherit">
            <tr><th colspan="2">[__Методы, унаследованные из] {inheritMethods[qualifiedName]}</th></tr>
            <tr><td><a href="{inheritMethods[path]}">{inheritMethods[name]}</a></td></tr>
        </table>
    [endeach.inheritMethods]
[endif.inheritMethods]
[if=constants]
    <h2 id="details_constants">[__Константы: детали]</h2>
    [each=constants]
        <div class="location">{constants[location]}</div>
        <h3 id="{constants[name]}">{constants[name]}</h3>
        <code class="signature">{constants[modifiers]} {constants[type]} <strong>{constants[name]}</strong>{constants[value]}</code>
        <div class="details">{constants[fullDesc]}</div>
        <hr>
    [endeach.constants]
[endif.constants]
[if=fields]
    <h2 id="details_fields">[__Поля: детали]</h2>
    [each=fields]
        <div class="location">{fields[location]}</div>
        <h3 id="{fields[name]}">{fields[name]}</h3>
        <code class="signature">{fields[modifiers]} {fields[type]} <strong><span class="green">{fields[name]}</span></strong>{fields[value]}</code>
        <div class="details">{fields[fullDesc]}</div>
        <hr>
    [endeach.fields]
[endif.fields]
[if=constructor]
    <h2 id="details_constructor">[__Конструктор: детали]</h2>
    <div class="location">{location}</div>
    <code class="signature" id="{name}">{modifiers} {type} <strong>{name}</strong>{signature}</code>
    <div class="details">
        {fullDesc}
        {tags}
    </div>
[endif.constructor]
[if=destructor]
    <h2 id="details_destructor">[__Деструктор: детали]</h2>
    <div class="location">{location}</div>
    <code class="signature" id="{name}">{modifiers} {type} <strong>{name}</strong>{signature}</code>
    <div class="details">
        {fullDesc}
        {tags}
    </div>
[endif.destructor]
[if=methods]
    <h2 id="details_methods">[__Методы: детали]</h2>
    [each=methods]
        <div class="location">{methods[location]}</div>
        <code class="signature" id="{methods[name]}">{methods[modifiers]} {methods[type]} <strong>{methods[name]}</strong> {methods[signature]}</code>
        <div class="details">
            {methods[fullDesc]}
            {methods[tags]}
        </div>
        <hr>
    [endeach.methods]
[endif.methods]
