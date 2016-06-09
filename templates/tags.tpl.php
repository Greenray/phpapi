<div class="finfo">
    <table class="hid">
    <!-- FOREACH $tags -->
        <tr>
            <td class="hid left w_100">$tags.name</td>
            <td class="hid right w_100 lilac">$tags.type</td>
            <!-- IF !empty($tags.var) -->
                <td class="hid blue w_100">$tags.var</td>
                <td class="hid">$tags.comment</td>
            <!-- ELSE -->
                <td class="hid" colspan="2">$tags.comment</td>
            <!-- END -->
        </tr>
    <!-- END -->
    </table>
</div>
