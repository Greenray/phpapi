<hr>
<div class="qualifiedName">{qualified}</div>
<h1>{qualifiedName}</h1>
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
[if=tags]{tags}[endif.tags]
<hr>
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
        <tr><td>{inheritFields[field]}</td></tr>
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
            <p class="description">{description}</p>
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
        <tr><td>{inheritMethods[method]}</td></tr>
    </table>
    [endeach.inheritMethods]
[endif.inheritMethods]
