<div class="header">
    <span style="float:right">$header</span>
    <!-- IF !empty($sections) -->
        <ul>
        <!-- FOREACH section = $sections -->
            <li<!-- IF !empty($section.selected) --> class="active"<!-- ENDIF -->>$section.title</li>
        <!-- ENDFOREACH -->
        </ul>
    <!-- ENDIF -->
</div>
<hr />
<div class="small_links">[__Frames]: <a href="{$path:}index.html" target="_top"> [__On]</a> | <a href="" target="_top"> [__Off]</a></div>
<hr />
<!-- IF !empty($class) -->
    <div class="small_links">
        [__Summary]: <a href="#summary_fields">[__Fields]</a> | <a href="#summary_methods">[__Methods]</a> | <a href="#summary_constructor">[__Constructor]</a>
        [__Details]: <a href="#details_fields">[__Fields]</a> | <a href="#details_methods">[__Methods]</a> | <a href="#details_constructor">[__Constructor]</a>
    </div>
<!-- ENDIF -->
<!-- IF !empty($function) -->
    <div class="small_links">
        [__Summary]: <a href="#summary_functions">[__Functions]</a>
        [__Details]: <a href="#details_functions">[__Functions]</a>
    </div>
<!-- ENDIF -->
<!-- IF !empty($global) -->
    <div class="small_links">
        [__Summary]: <a href="#summary_globals">[__Globals]</a>
        [__Details]: <a href="#details_globals">[__Globals]</a>
    </div>
<!-- ENDIF -->
