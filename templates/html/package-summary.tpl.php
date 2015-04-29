<h1>[__Namespace] $namespace</h1>
<!-- IF !empty($shortView) -->
    <div class="comment">$shortView</div>
    <!-- IF !empty($overView) -->
        <dl><dt>[__See]:</dt><dd><b><a href="#overview_description">[__Description]</a></b></dd></dl>
    <!-- ENDIF -->
    <br />
<!-- ENDIF -->
<!-- IF isset($classes) && !empty($classes) -->
    <table class="title">
        <tr><th colspan="2" class="title">[__Classes]: [__summary]</th></tr>
        <!-- FOREACH class = $classes -->
            <tr>
                <td class="name"><a href="$class.path">$class.name</a></td>
                <td class="description">$class.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($interfaces) -->
    <table class="title">
        <tr><th colspan="2" class="title">[__Interfaces]: [__summary]</th></tr>
        <!-- FOREACH interface = $interfaces -->
            <tr>
                <td class="name"><a href="$interface.path">$interface.name</a></td>
                <td class="description">$interface.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($traits) -->
    <table class="title">
        <tr><th colspan="2" class="title">[__Traits]: [__summary]</th></tr>
        <!-- FOREACH trait = $traits -->
            <tr>
                <td class="name"><a href="$trait.path">$trait.name</a></td>
                <td class="description">$trait.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($exceptions) -->
    <table class="title">
        <tr><th colspan="2" class="title">[__Exceptions]: [__summary]</th></tr>
        <!-- FOREACH exception = $exceptions -->
            <tr>
                <td class="name"><a href="$exception.path">$exception.name</a></td>
                <td class="description">$exception.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($functions) -->
    <table class="title">
        <tr><th colspan="2" class="title">[__Functions]: [__summary]</th></tr>
        <!-- FOREACH function = $functions -->
            <tr>
                <td class="name"><a href="$function.path">$function.name</a></td>
                <td class="description">$function.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($globals) -->
    <table class="title">
        <tr><th colspan="2" class="title">[__Globals]: [__summary]</th></tr>
        <!-- FOREACH global = $globals -->
            <tr>
                <td class="name"><a href="$global.path">$global.name</a></td>
                <td class="description">$global.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($overView) -->
    <h1>[__Namespace] $namespace: [__Description]</h1>
    <div class="comment" id="overview_description">$overView</div>
<!-- ENDIF -->
