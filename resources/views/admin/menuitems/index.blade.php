<link rel="stylesheet" href="{{ asset('/dist/plugins/nestable/jquery.nestable.css') }}">

@php
function buildMenu($source, $parent_id = 0) {
    echo '<ol class="dd-list">';

    foreach( $source as $menu) {
        echo '<li class="dd-item dd3-item" data-id="'.$menu['id'].'">'.
             '<div class="dd-handle dd3-handle"></div>'. 
             '<div class="dd3-content" data-toggle="modal" data-target="#modalBox" data-url="' . route('admin.menuitem.edit', $menu['id']) . '">'.
             '<i class="fa fa-'. $menu['icon'] .'"></i> '.
             '<b>'.$menu['title'].'</b>'.
             ( ($menu['permission']) ? ' <span class="badge badge-secondary ml-1 float-right"><i class="text-uppercase">'.__('menu.fields.permission').'</i>: '.$menu['permission'].'</span>' : null).
             ( ($menu['route']) ? ' <span class="badge badge-secondary ml-1 float-right"><i class="text-uppercase">'.__('menu.fields.route').'</i>: '.$menu['route'].'</span>' : null).
             '</div>';

        if ($menu['submenu']) {
            buildMenu( @$menu['submenu'], $menu['id']);
        }
        echo '</li>';
    }
    echo '</ol>';
}
@endphp

{{ buildMenu( $menuTree ) }}

<script src="{{ asset('/dist/plugins/nestable/jquery.nestable.js') }}"></script>
<script>
    $(document).ready(function() {
        var updateOutput = function(e) {
            var list   = e.length ? e : $(e.target),
                output = list.data('output');

            $.ajax({
                url: '{{ route("admin.menu.menu_update") }}',
                type: 'get',
                contentType: 'application/json',
                data: 'out='+JSON.stringify(list.nestable('serialize')),
                traditional: true,
                success: function (data) {
                },
            });
        };

        $('#nestable').nestable().on('change', updateOutput);

        $('#nestable-menu').on('click', function(e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
    });
</script>