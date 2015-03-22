<hr>
<h1>[__Глобальные элементы]</h1>
<hr>
<table id="summary_globals">
    <tr><th colspan="2">[__Глобальные элементы: общий обзор]</th></tr>
    [each=globals]
        <tr>
            <td class="type w_200">{globals[modifiers]} {globals[type]}</td>
            <td class="description">
                <code><p><a href="#{globals[name]}"><span class="lilac">{globals[name]}</span></a>[if=globals[value]]{globals[value]}[endif.globals]</p></code>
                <p class="description">{globals[shortDesc]}</p>
            </td>
        </tr>
    [endeach.globals]
</table>
<h2 id="details_globals">[__Глобальные элементы: детали]</h2>
[each=globals]
    <div class="location">{globals[location]}</div>
    <h3 id="{globals[name]}">{globals[name]}</h3>
    <code class="signature">{globals[modifiers]} {globals[type]} <strong>{globals[name]}</strong>{globals[value]}</code>
    <div class="details">{globals[fullDesc]}</div>
    <hr>
[endeach.globals]
