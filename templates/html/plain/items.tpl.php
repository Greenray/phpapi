<h1>[__Namespaces]</h1>
<ul>
<!-- FOREACH package = $packages -->
    <li><a href="{$package.path:}package-summary.html">$package.name</a></li>
<!-- ENDFOREACH -->
</ul>
<!-- IF !empty($classes) -->
        <h2>[__Classes]</h2>
        <ul>
        <!-- FOREACH class = $classes -->
            <li>
                <a href="{$class.packpath:}package-summary.html" title="$class.packname">$class.packname</a> \
                <a href="$class.path" title="$class.name">$class.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($interfaces) -->
        <h2>[__Interfaces]</h2>
        <ul>
        <!-- FOREACH interface = $interfaces -->
            <li>
                <a href="{$interface.packpath:}package-summary.html" title="$interface.packname">$interface.packname</a> \
                <a href="$interface.path" title="$interface.name">$interface.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($traits) -->
        <h2>[__Traits]</h2>
        <ul>
        <!-- FOREACH trait = $traits -->
            <li>
                <a href="{$trait.packpath:}package-summary.html" title="$trait.packname">$trait.packname</a> \
                <a href="$trait.path" title="$trait.name">$trait.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($exceptions) -->
        <h2>[__Exceptions]</h2>
        <ul>
        <!-- FOREACH exception = $exceptions -->
            <li>
                <a href="{$exception.packpath:}package-summary.html" title="$exception.packname">$exception.packname</a> \
                <a href="$exception.path" title="$exception.name">$exception.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($functions) -->
        <h2>[__Functions]</h2>
        <ul>
        <!-- FOREACH function = $functions -->
            <li>
                <a href="{$function.packpath:}package-summary.html" title="$function.packname">$function.packname</a> \
                <a href="$function.path" title="$function.name">$function.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($globals) -->
        <h2>[__Globals]</h2>
        <ul>
        <!-- FOREACH global = $globals -->
            <li><a href="$global.path" title="$global.name">$global.name</a></li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
