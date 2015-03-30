<div class="finfo">
    <table class="hid">
    [each=tags]
        <tr>
            <td class="hid left w_100">{tags[name]}</td>
            <td class="hid right w_100 lilac">{tags[type]}</td>
            [if_else=tags[var]]
                <td class="hid blue w_100">{tags[var]}</td>
                <td class="hid">{tags[comment]}</td>
            [else]
                <td class="hid" colspan="2">{tags[comment]}</td>
            [/else]
        </tr>
    [/each.tags]
    </table>
</div>
