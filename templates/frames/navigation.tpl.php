<div class="header">
    <span style="float:right">$header</span>
    <!-- IF !empty($sections) -->
        <ul>
        <!-- FOREACH $sections -->
            <li<!-- IF !empty($sections.selected) --> class="active"<!-- END -->>$sections.title</li>
        <!-- END -->
        </ul>
    <!-- END -->
</div>
<hr />
<div class="small_links">__Frames__: <a href="[$path]index.html" target="_top"> __On__</a> | <a href="" target="_top"> __Off__</a></div>
<hr />
<!-- IF !empty($class) -->
    <div class="small_links">
        <a href="#details_fields">__Fields__</a> | <a href="#details_methods">__Methods__</a> | <a href="#details_constructor">__Constructor__</a>
    </div>
<!-- END -->
<!-- IF !empty($function) -->
    <div class="small_links"><a href="#details_functions">__Functions__</a></div>
<!-- END -->
<!-- IF !empty($global) -->
    <div class="small_links"><a href="#details_globals">__Globals__</a></div>
<!-- END -->
