                @if (is_string($item))
                <li class="nav-header">{{ $item }}</li>
                @else
                <li class="nav-item @if(isset($item['submenu'])){{'has-treeview'}}@endif @if($item['class'] == 'active treeview active'){{'menu-open'}}@endif">
                    <a href="{{ $item['href'] }}" class="nav-link @if($item['class'] == 'active treeview active' || $item['class'] == 'active'){{'active'}}@endif" @if (isset($item['target'])) target="{{ $item['target'] }}" @endif>
                        <i class="nav-icon fa fa-fw fa-{{ isset($item['icon']) ? $item['icon'] : 'circle' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
                        <p>
                            {{ $item['text'] }}
                            @if (isset($item['submenu']))
                                <i class="fa fa-angle-left right"></i>
                            @endif
                            @if (isset($item['label']))
                                <span class="badge badge-{{ isset($item['label_color']) ? $item['label_color'] : 'primary' }} right">{{ $item['label'] }}</span>
                            @endif
                        </p>
                    </a>
                    @if (isset($item['submenu']))
                        <ul class="nav nav-treeview">
                            @each('adminlte::partials.menu-item', $item['submenu'], 'item')
                        </ul>
                    @endif
                </li>
                @endif