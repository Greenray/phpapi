<hr />
<div class="package">[__Namespace] $package</div>
<h1>[__Globals]</h1>
<!-- IF !empty($globals) -->
    <table id="summary_globals">
        <tr><th colspan="2">[__Globals]: [__summary]</th></tr>
        <!-- FOREACH global = $globals -->
            <tr>
                <td class="type w_200">$global.modifiers $global.type</td>
                <td class="description">
                    <pre><a href="#$global.name"><span class="lilac">$global.name</span></a>$global.value</pre>
                    <p class="description">$global.shortDesc</p>
                </td>
            </tr>
            <!-- ENDFOREACH -->
    </table>
    <h2 id="details_globals">[__Globals]: [__details]</h2>
    <!-- FOREACH global = $globals -->
        <div class="location">$global.location</div>
        <code id="$global.name" class="arguments"><pre>$global.modifiers $global.type <strong>$global.name</strong>$global.value</pre></code>
        <div class="details">$global.fullDesc</div>
        <hr />
    <!-- ENDFOREACH -->
<!-- ENDIF -->
