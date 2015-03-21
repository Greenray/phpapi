[each=tree]
    [if=tree[start]]<ul>[endif.tree]
        <li>
            <a href="{tree[path]}">{tree[name]}</a>

        </li>
[endeach.tree]
[if=tree[stop]]</ul>[endif.tree]