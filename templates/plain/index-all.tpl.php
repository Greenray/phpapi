[each=letters]<a href="#letter{letters[letter]}"> {letters[letter]}</a>[/each.letters]
[each=elements]
    <table><caption id="letter{elements[char]}">{elements[char]}</caption>
    [each=elements[letter]]
        <tr>
            <td class="w_200"><a href="{letter[path]}">{letter[name]}</a></td>
            <td class="w_400">{letter[element]} {letter[in]} <a href="{letter[inPath]}">{letter[inName]}</a></td>
            <td>{letter[description]}</td>
        </tr>
    [/each.elements[letter]]
    </table>
[/each.elements]
