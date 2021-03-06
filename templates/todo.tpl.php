<h1>Todo</h1>
<!-- IF !empty($menu) -->
    <ul>
    <!-- FOREACH $menu -->
        <!-- IF !empty($menu.class) -->    <li><a href="#todo_classes">__Classes__</a></li><!-- END -->
        <!-- IF !empty($menu.field) -->    <li><a href="#todo_fields">__Fields__</a></li><!-- END -->
        <!-- IF !empty($menu.method) -->   <li><a href="#todo_methods">__Methods__</a></li><!-- END -->
        <!-- IF !empty($menu.function) --> <li><a href="#todo_functions">__Functions__</a></li><!-- END -->
        <!-- IF !empty($menu.global) -->   <li><a href="#todo_globals">__Globals__</a></li><!-- END -->
    <!-- END -->
    </ul>
<!-- END -->
<!-- IF !empty($classes) -->
    <table id="todo_classes">
        <tr><th colspan="2" class="title">__Classes__</th></tr>
        <!-- FOREACH $classes -->
            <tr>
                <td class="name"><a href="$classes.path">$classes.name</a></td>
                <td class="description">$classes.desc</td>
            </tr>
            <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($fields) -->
    <table id="todo_fields">
        <tr><th colspan="2" class="title">__Fields__</th></tr>
        <!-- FOREACH $fields -->
            <tr>
                <td class="name"><a href="$fields.path">$fields.name</a></td>
                <td class="description">$fields.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($methods) -->
    <table id="todo_methods">
        <tr><th colspan="2" class="title">__Methods__</th></tr>
        <!-- FOREACH $methods -->
            <tr>
                <td class="name"><a href="$methods.path">$methods.name</a></td>
                <td class="description">$methods.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($functions) -->
    <table id="todo_functions"">
        <tr><th colspan="2" class="title">__Functions__</th></tr>
        <!-- FOREACH $functions -->
            <tr>
                <td class="name"><a href="$functions.path">$functions.name</a></td>
                <td class="description">$functions.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
<!-- IF !empty($globals) -->
    <table id="todo_globals">
        <tr><th colspan="2" class="title">__Globals__</th></tr>
        <!-- FOREACH $globals -->
            <tr>
                <td class="name"><a href="$globals.path">$globals.name</a></td>
                <td class="description">$globals.desc</td>
            </tr>
        <!-- END -->
    </table>
<!-- END -->
