<div class="package">[__Namespace] $package</div>
<h1>[__Functions]</h1>
<hr />
<!-- IF !empty($functions) -->
    <table id="summary_functions" class="title">
        <tr><th colspan="2" class="title">[__Functions]: [__summary]</th></tr>
        <!-- FOREACH function = $functions -->
            <tr>
                <td class="type w_200">$function.modifiers $function.type</td>
                <td class="description">
                    <p><a href="#$function.name"><span class="lilac">$function.name</span></a>$function.arguments</p>
                    <p class="description">$function.shortDesc</p>
                </td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
    <h2 id="details_functions">[__Functions]: [__details]</h2>
    <!-- FOREACH function = $functions -->
        <div class="location">$function.location</div>
        <code id="$function.name" class="arguments">$function.modifiers $function.type <strong>$function.name</strong> $function.arguments</code>
        <div class="details">
            $function.fullDesc
            $function.parameters
        </div>
    <!-- ENDFOREACH -->
<!-- ENDIF -->
