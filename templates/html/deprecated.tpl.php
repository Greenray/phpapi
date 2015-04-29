<h1>[__Deprecated]</h1>
<!-- IF !empty($menu) -->
    <ul>
    <!-- FOREACH menu = $menu -->
        <!-- IF !empty($menu.class) -->    <li><a href="#deprecated_classes">[__Classes]</a></li><!-- ENDIF -->
        <!-- IF !empty($menu.field) -->    <li><a href="#deprecated_fields">[__Fields]</a></li><!-- ENDIF -->
        <!-- IF !empty($menu.method) -->   <li><a href="#deprecated_methods">[__Methods]</a></li><!-- ENDIF -->
        <!-- IF !empty($menu.function) --> <li><a href="#deprecated_functions">[__Functions]</a></li><!-- ENDIF -->
        <!-- IF !empty($menu.global) -->   <li><a href="#deprecated_globals">[__Globals]</a></li><!-- ENDIF -->
    <!-- ENDFOREACH -->
    </ul>
<!-- ENDIF -->
<!-- IF !empty($classes) -->
    <table id="deprecated_classes">
        <tr><th colspan="2" class="title">[__Classes]</th></tr>
        <!-- FOREACH class = $classes -->
            <tr>
                <td class="name"><a href="$class.path">$class.name</a></td>
                <td class="description">$class.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($fields) -->
    <table id="deprecated_fields">
        <tr><th colspan="2" class="title">[__Fields]</th></tr>
        <!-- FOREACH field = $fields -->
            <tr>
                <td class="name"><a href="$field.path">$field.name</a></td>
                <td class="description">$field.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($methods) -->
    <table id="deprecated_methods">
        <tr><th colspan="2" class="title">[__Methods]</th></tr>
        <!-- FOREACH method = $methods -->
            <tr>
                <td class="name"><a href="$method.path">$method.name</a></td>
                <td class="description">$method.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($functions) -->
    <table id="deprecated_functions"">
        <tr><th colspan="2" class="title">[__Functions]</th></tr>
        <!-- FOREACH function = $functions -->
            <tr>
                <td class="name"><a href="$function.path">$function.name</a></td>
                <td class="description">$function.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
<!-- IF !empty($globals) -->
    <table id="deprecated_globals">
        <tr><th colspan="2" class="title">[__Globals]</th></tr>
        <!-- FOREACH global = $globals -->
            <tr>
                <td class="name"><a href="$global.path">$global.name</a></td>
                <td class="description">$global.desc</td>
            </tr>
        <!-- ENDFOREACH -->
    </table>
<!-- ENDIF -->
