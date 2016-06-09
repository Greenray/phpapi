<!-- FOREACH $letters --><a href="#letter$letters.letter"> $letters.letter</a><!-- END -->
<!-- FOREACH $elements -->
    <table><caption id="letter$elements.char">$elements.char</caption>
        <!-- FOREACH $elements.letter -->
            <tr>
                <td class="w_200"><a href="$letter.path">$letter.name</a></td>
                <td class="w_400">$letter.element $letter.in <a href="$letter.inPath">$letter.inName</a></td>
                <td>$letter.description</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
