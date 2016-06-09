<h1>$title</h1>
<!-- IF !empty($description) -->
    <div class="comment">$description</div>
    <!-- IF !empty($overview) -->
        <div class="comment"><strong>__See__: <a href="#overview_description">$overviewFile</a></strong></div>
    <!-- END -->
<!-- END -->
<table class="title">
    <tr><th colspan="2" class="title">__Namespaces__</th></tr>
    <!-- FOREACH $packages -->
        <tr>
            <td class="name"><a href="[$packages.path]package-summary.html">$packages.name</a></td>
            <td class="description">$packages.desc</td>
        </tr>
    <!-- END -->
</table>
<!-- IF !empty($overview) -->
    <div class="comment" id="overview_description">$overview</div>
<!-- END -->