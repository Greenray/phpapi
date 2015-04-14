<hr />
<h1>{title}</h1>
[if=description]
    <div class="comment">{description}</div>
    <div class="comment"><strong>[__Смотри]: <a href="#overview_description">{overviewFile}</a></strong></div>
[/if.description]
<table class="title">
    <tr><th colspan="2" class="title">[__Пространства имен]</th></tr>
    [each=package]
        <tr>
            <td class="name"><a href="{package[path]}package-summary.html">{package[name]}</a></td>
            <td class="description">{package[desc]}</td>
        </tr>
    [/each.package]
</table>
[if=overview]<div class="comment" id="overview_description">{overview}</div>[/if.overview]
