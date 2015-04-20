<hr />
<div class="package">[__Пространство имен] {package}</div>
<h1>[__Функции]</h1>
<hr />
[if=function]
    <table id="summary_functions" class="title">
        <tr><th colspan="2" class="title">[__Функции]: [__общий обзор]</th></tr>
        [each=function]
            <tr>
                <td class="type w_200">{function[modifiers]} {function[type]}</td>
                <td class="description">
                    <p><a href="#{function[name]}"><span class="lilac">{function[name]}</span></a>{function[arguments]}</p>
                    <p class="description">{function[shortDesc]}</p>
                </td>
            </tr>
        [/each.function]
    </table>
    <h2 id="details_functions">[__Функции]: [__детали]</h2>
    [each=function]
        <div class="location">{function[location]}</div>
        <code id="{function[name]}" class="arguments">{function[modifiers]} {function[type]} <strong>{function[name]}</strong> {function[arguments]}</code>
        <div class="details">
            {function[fullDesc]}
            {function[parameters]}
        </div>
        <hr />
    [/each.function]
[/if.function]
