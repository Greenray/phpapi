<div class="finfo">
    <table class="hid">
    <!-- FOREACH tag = $tags -->
        <tr>
            <td class="hid left w_100">$tag.name</td>
            <td class="hid right w_100 lilac">$tag.type</td>
            <!-- IF !empty($tag.var) -->
                <td class="hid blue w_100">$tag.var</td>
                <td class="hid">$tag.comment</td>
            <!-- ELSE -->
                <td class="hid" colspan="2">$tag.comment</td>
            <!-- ENDIF -->
        </tr>
    <!-- ENDFOREACH -->
    </table>
</div>
