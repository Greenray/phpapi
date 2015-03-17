<hr>
<h1>[__Глобальные элементы]</h1>
<hr>
<table id="summary_globals">
    <tr><th colspan="2">[__Общий обзор]</th></tr>
    [each=global]
        <tr>
            <td class="type w_200">{global[modifiers]} {global[type]}</td>
            <td class="description">
                <code><p><a href="#{global[name]}"><span class="lilac">{global[name]}</span></a>[if=global[value]]{global[value]}[endif.global]</p></code>
                <p class="description">{global[description]}</p>
            </td>
        </tr>
    [endeach.global]
</table>
<h2 id="details_globals">[__Детали]</h2>
[each=globals]
    [if=globals[location]]<div class="location">{globals[location]}</div>[endif.globals]
    <h3 id="{globals[name]}">{globals[name]}</h3>
    <code class="signature">{globals[modifiers]} {globals[type]} <strong>{globals[name]}</strong>{globals[value]}</code>
    <div class="details">{globals[description]}</div>
    {globals[tags]}
    <hr>
[endeach.globals]
