<!-- FOREACH letter = $letters --><a href="#letter$letter.letter"> $letter.letter</a><!-- ENDFOREACH -->
<!-- FOREACH element = $elements -->
    <table><caption id="letter$element.char">$element.char</caption>
        <!-- FOREACH letter = $element.letter -->
            <tr>
                <td class="w_200"><a href="$letter.path">$letter.name</a></td>
                <td class="w_400">$letter.element $letter.in <a href="$letter.inPath">$letter.inName</a></td>
                <td>$letter.description</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDFOREACH -->
