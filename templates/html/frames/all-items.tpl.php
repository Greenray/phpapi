<body id="frame">
    <h1>[__Полный список]</h1>
    <!-- IF !empty($classes) -->
        <h2>[__Classes]</h2>
        <ul>
        <!-- FOREACH class = $classes -->
            <li>
                <a href="{$class.packpath:}package-summary.html" title="$class.packname" target="main">$class.packname</a> \
                <a href="$class.path" title="$class.name" target="main">$class.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($interfaces) -->
        <h2>[__Interfaces]</h2>
        <ul>
        <!-- FOREACH interface = $interfaces -->
            <li>
                <a href="{$interface.packpath:}package-summary.html" title="$interface.packname" target="main">$interface.packname</a> \
                <a href="$interface.path" title="$interface.name" target="main">$interface.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($traits) -->
        <h2>[__Traits]</h2>
        <ul>
        <!-- FOREACH trait = $traits -->
            <li>
                <a href="{$trait.packpath:}package-summary.html" title="$trait.packname" target="main">$trait.packname</a> \
                <a href="$trait.path" title="$trait.name" target="main">$trait.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($exceptions) -->
        <h2>[__Exceptions]</h2>
        <ul>
        <!-- FOREACH exception = $exceptions -->
            <li>
                <a href="{$exception.packpath:}package-summary.html" title="$exception.packname" target="main">$exception.packname</a> \
                <a href="$exception.path" title="$exception.name" target="main">$exception.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($functions) -->
        <h2>[__Functions]</h2>
        <ul>
        <!-- FOREACH function = $functions -->
            <li>
                <a href="{$function.packpath:}package-summary.html" title="$function.packname" target="main">$function.packname</a> \
                <a href="$function.path" title="$function.name" target="main">$function.name</a>
            </li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($globals) -->
        <h2>[__Globals]</h2>
        <ul>
        <!-- FOREACH global = $globals -->
            <li><a href="$global.path" title="$global.name" target="main">$global.name</a></li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
</body>
