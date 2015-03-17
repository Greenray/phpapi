<hr>
<h1>[__Функции]</h1>
<hr>
[if=function]
    <table id="summary_function" class="title">
        <tr><th colspan="2" class="title">[__Функции: общий обзор]</th></tr>
        [each=function]
            <tr>
                <td class="type w_200">{function[modifiers]} {function[type]}</td>
                <td class="description">
                    <p class="name"><a href="#{function[name]}"><span class="lilac">{function[name]}</span></a>{function[signature]}</p>
                    [if=function[description]]<p class="description">{function[description]}</p>[endif.function]
                </td>
            </tr>
        [endeach.function]
    </table>
    <h2 id="detail_function">[__Функции: подробно]</h2>
    [each=functions]
        <h3 id="{functions[name]}">{functions[name]}</h3>
        <code class="signature">{functions[modifiers]} {functions[type]} <strong>{functions[name]}</strong>{functions[signature]}</code>
        [if=functions[description]]
            <div class="details">
                {functions[description]}
                {functions[tags]}
            </div>
        [endif.functions]
        <hr>
    [endeach=functions]
[endif.function]
