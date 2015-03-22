<hr>
<h1>[__Функции]</h1>
<hr>
[if=functions]
    <table id="summary_functions" class="title">
        <tr><th colspan="2" class="title">[__Функции: общий обзор]</th></tr>
        [each=functions]
            <tr>
                <td class="type w_200">{functions[modifiers]} {functions[type]}</td>
                <td class="description">
                    <p class="name"><a href="#{functions[name]}"><span class="lilac">{functions[name]}</span></a>{functions[signature]}</p>
                    <p class="description">{functions[shortDesc]}</p>
                </td>
            </tr>
        [endeach.functions]
    </table>
    <h2 id="details_functions">[__Функции: детали]</h2>
    [each=functions]
        <div class="location">{functions[location]}</div>
        <h3 id="{functions[name]}">{functions[name]}</h3>
        <code class="signature">{functions[modifiers]} {functions[type]} <strong>{functions[name]}</strong> {functions[signature]}</code>
        <div class="details">
            {functions[fullDesc]}
            {functions[tags]}
        </div>
        <hr>
    [endeach.functions]
[endif.functions]
