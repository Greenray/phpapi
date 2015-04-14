<div class="finfo">
    <table class="hid">
    [each=tag]
        <tr>
            <td class="hid left w_100">{tag[name]}</td>
            <td class="hid right w_100 lilac">{tag[type]}</td>
            [if_else=tag[var]]
                <td class="hid blue w_100">{tag[var]}</td>
                <td class="hid">{tag[comment]}</td>
            [else]
                <td class="hid" colspan="2">{tag[comment]}</td>
            [/else]
        </tr>
    [/each.tag]
    </table>
</div>
