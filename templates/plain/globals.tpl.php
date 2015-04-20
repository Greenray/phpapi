<hr />
<div class="package">[__Пространство имен] {package}</div>
<h1>[__Глобальные элементы]</h1>
<table id="summary_globals">
    <tr><th colspan="2">[__Глобальные элементы]: [__общий обзор]</th></tr>
    [each=global]
        <tr>
            <td class="type w_200">{global[modifiers]} {global[type]}</td>
            <td class="description">
                <pre><a href="#{global[name]}"><span class="lilac">{global[name]}</span></a>[if=global[value]]{global[value]}[/if.global]</pre>
                <p class="description">{global[shortDesc]}</p>
            </td>
        </tr>
    [/each.global]
</table>
<h2 id="details_globals">[__Глобальные элементы]: [__детали]</h2>
[each=global]
    <div class="location">{global[location]}</div>
    <code id="{global[name]}" class="arguments"><pre>{global[modifiers]} {global[type]} <strong>{global[name]}</strong>{global[value]}</pre></code>
    <div class="details">{global[fullDesc]}</div>
    <hr />
[/each.global]
