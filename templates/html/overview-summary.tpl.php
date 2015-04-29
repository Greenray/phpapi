<h1>$title</h1>
<!-- IF !empty($description) -->
    <div class="comment">$description</div>
    <!-- IF !empty($overview) -->
        <div class="comment"><strong>[__See]: <a href="#overview_description">$overviewFile</a></strong></div>
    <!-- ENDIF -->
<!-- ENDIF -->
<table class="title">
    <tr><th colspan="2" class="title">[__Namespaces]</th></tr>
    <!-- FOREACH package = $packages -->
        <tr>
            <td class="name"><a href="{$package.path:}package-summary.html">$package.name</a></td>
            <td class="description">$package.desc</td>
        </tr>
    <!-- ENDFOREACH -->
</table>
<!-- IF !empty($overview) -->
    <div class="comment" id="overview_description">$overview</div>
<!-- ENDIF -->