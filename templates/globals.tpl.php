<hr />
<div class="package">__Namespace__ $package</div>
<!-- IF !empty($globals) -->
    <h2 id="details_globals">__Globals__</h2>
    <!-- FOREACH $globals -->
        <div class="location">$globals.location</div>
        <code id="$globals.name" class="arguments"><pre>$globals.modifiers $globals.type <strong>$globals.name</strong>$globals.value</pre></code>
        <div class="details">$globals.fullDesc</div>
        <hr />
    <!-- END -->
<!-- END -->
