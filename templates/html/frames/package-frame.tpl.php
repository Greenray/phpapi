<body id="frame">
    <h1><a href="package-summary.html" target="main">$package</a></h1>
    <!-- IF !empty($classes) -->
        <h2>[__Classe]</h2>
        <ul>
            <!-- FOREACH class = $classes -->
                <li><a href="$class.allpath" target="main">$class.name</a></li>
            <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($interfaces) -->
        <h2>[__Interfaces]</h2>
        <ul>
        <!-- FOREACH interface = $interfaces -->
            <li><a href="$interface.allpath" target="main">$interface.name</a></li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($traits) -->
        <h2>[__Traits]</h2>
        <ul>
        <!-- FOREACH trait = $traits -->
            <li><a href="$trait.allpath" target="main">$trait.name</a></li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($exceptions) -->
        <h2>[__Exceptions]</h2>
        <ul>
        <!-- FOREACH exception = $exceptions -->
            <li><a href="$exception.allpath" target="main">$exception.name</a></li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($functions) -->
        <h2>[__Functions]</h2>
        <ul>
        <!-- FOREACH function = $functions -->
            <li><a href="$function.allpath" target="main">$function.name</a></li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
    <!-- IF !empty($globals) -->
        <h2>[__Globals]</h2>
        <ul>
        <!-- FOREACH global = $globals -->
            <li><a href="$global.allpath" target="main">$global.name</a></li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
</body>
