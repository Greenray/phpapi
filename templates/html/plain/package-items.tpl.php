<h1>[__Namespaces]</h1>
<ul>
<!-- FOREACH package = $packages -->
    <li><a href="{$package.path:}package-summary.html">$package.name</a></li>
<!-- ENDFOREACH -->
</ul>
<h1>[__Namespace] $current</h1>
<!-- IF !empty($classes) -->
    <h2>[__Classes]</h2>
    <ul>
    <!-- FOREACH class = $classes -->
        <li><a href="$class.path" title="$class.package \ $class.name">$class.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
<!-- ENDIF -->
<!-- IF !empty($interfaces) -->
    <h2>[__Interfaces]</h2>
    <ul>
    <!-- FOREACH interface = $interfaces -->
        <li><a href="$interface.path" title="$interface.package \ $interface.name">$interface.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
<!-- ENDIF -->
<!-- IF !empty($traits) -->
    <h2>[__Traits]</h2>
    <ul>
    <!-- FOREACH trait = $traits -->
        <li><a href="$trait.path" title="$trait.package \ $trait.name">$trait.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
<!-- ENDIF -->
<!-- IF !empty($exceptions) -->
    <h2>[__Exceptions]</h2>
    <ul>
    <!-- FOREACH exception = $exceptions -->
        <li><a href="$exception.path" title="$exception.package">$exception.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
<!-- ENDIF -->
<!-- IF !empty($functions) -->
    <h2>[__Functions]</h2>
    <ul>
    <!-- FOREACH function = $functions -->
        <li><a href="$function.path" title="$function.package">$function.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
<!-- ENDIF -->
<!-- IF !empty($globals) -->
    <h2>[__Globals]</h2>
    <ul>
    <!-- FOREACH global = $globals -->
        <li><a href="$global.path" title="$global.package">$global.name</a></li>
    <!-- ENDFOREACH -->
    </ul>
<!-- ENDIF -->
