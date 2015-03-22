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
[if=constant]
    <table id="summary_constants">
        <tr><th colspan="2">[__Константы: общий обзор]</th></tr>
        [each=constant]
            <tr>
                <td class="type w_200">{constant[modifiers]} {constant[type]}</td>
                <td class="description">
                    <code><p><a href="#{constant[name]}"><span class="lilac">{constant[name]}</span></a>{constant[value]}</p></code>
                    <p class="description">{constant[description]}</p>
                </td>
            </tr>
        [endeach.constant]
    </table>
[endif.constant]
[if=field]
    <table id="summary_fields">
        <tr><th colspan="2">[__Поля: общий обзор]</th></tr>
        [each=field]
            <tr>
                <td class="type w_200">{field[modifiers]} {field[type]}</td>
                <td class="description">
                    <code>
                        <p>
                            <a href="#{field[name]}"><span class="green">${field[name]}</span></a>
                            [if=field[value]]{field[value]}[endif.field]
                        </p>
                        </code>
                    <p class="description">{field[description]}</p>
                </td>
            </tr>
        [endeach.field]
    </table>
[endif.field]
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
[if=method]
    <table id="summary_methods">
        <tr><th colspan="2">[__Методы: общий обзор]</th></tr>
        [each=method]
            <tr>
                <td class="type w_200">{method[modifiers]} {method[type]}</td>
                <td class="description">
                    <code><p><a href="#{method[name]}"><strong><span class="black">{method[name]}</span></strong></a>{method[signature]}</p></code>
                    <p class="description">{method[description]}</p>
                </td>
            </tr>
        [endeach.method]
    </table>
[endif.method]
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
        [if=constants[description]]<div class="details">{constants[description]}</div>
        [endif.constants]
        <hr>
    [endeach.constants]
[endif.constants]
[if=fields]
    <h2 id="details_fields">[__Поля: детали]</h2>
    [each=fields]
        <div class="location">{fields[location]}</div>
        <h3 id="{fields[name]}">{fields[name]}</h3>
        <code class="signature">{fields[modifiers]} {fields[type]} <strong><span class="green">{fields[name]}</span></strong>{fields[value]}</code>
        [if=fields[description]]<div class="details">{fields[description]}</div>[endif.fields]
        <hr>
    [endeach.fields]
[endif.fields]
[if=constructor]
    <h2 id="details_constructor">[__Конструктор: детали]</h2>
    <div class="location">{location}</div>
    <code class="signature" id="{name}">{modifiers} {type}<strong>{name}</strong>{signature}</code>
    <div class="details">
        {fullDesc}
        {tags}
    </div>
[endif.constructor]
[if=methods]
    <h2 id="details_methods">[__Методы: детали]</h2>
    [each=methods]
        <div class="location">{methods[location]}</div>
        <code class="signature" id="{methods[name]}">{methods[modifiers]} {methods[type]} <strong>{methods[name]}</strong> {methods[signature]}</code>
        <div class="details">
            {methods[description]}
            {methods[tags]}
        </div>
        <hr>
    [endeach.methods]
[endif.methods]
