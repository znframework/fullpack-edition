<nav> 
    {[$menus = Menus::where('active', '1', 'and')->where('submenu_id', '0')->orderBy('order_id')->result();]}

    <ul class="nav">
        @foreach( $menus as $menu ):
            {[$submenu = Menus::where('active', '1', 'and')->where('submenu_id', $menu->id)->orderBy('order_id')->result();]}
            <li>
                @if( empty($submenu) ):
                    <a href="{{$site . $menu->url}}" class="{{active($menu->url)}}"><i class="fa {{$menu->icon}}"></i> <span>{{$dict->{$menu->name} ?? $menu->name }}</span></a>
                @else:
                    <a href="#{{$menu->name}}" data-toggle="collapse" class="collapsed {{active($menu->name)}}"><i class="fa {{$menu->icon}}"></i> <span>{{$dict->{$menu->name} ?? $menu->name }}</span></a>
                    <div id="{{$menu->name}}" class="collapse {{active($menu->name, 'in')}}">
                        <ul class="nav">
                        @foreach( $submenu as $sub ):
                            <li><a href="{{$site . $sub->url}}" class="{{active($sub->url)}}">{{$dict->{$sub->name} ?? $sub->name }}</a></li>
                        @endforeach:
                        </ul>
                    </div>
                @endif:

            </li>
        @endforeach:

        <li><a href="{{$site . 'menus'}}" class="{{active('menus/main')}}"><i class="fa fa-list"></i> <span>{{$dict->menus}}</span></a></li>
    </ul>
</nav>