<div class="hide" id="loading"></div>

<!-- WRAPPER -->
<div id="wrapper">
    <!-- NAVBAR -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="brand">
            <a href="{{$site}}"><img src="{{THEMES_URL}}img/logo-dark.png" alt="ZN Framework" class="img-responsive logo"></a>
        </div>
        <div class="container-fluid">
            <div class="navbar-btn">
                <button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
            </div>
            <div class="navbar-form navbar-left">
                <div class="input-group">
                    <input type="text" onkeydown="ajaxSearch(this, event)" class="form-control" placeholder="{{$dict->search}} ...">
                    <span class="input-group-btn"><button type="button" class="btn btn-primary">Go</button></span>
                </div>
            </div>

            <div id="navbar-menu">
                <ul class="nav navbar-nav navbar-right">
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="{{photo($user->photo)}}" class="img-circle" alt="Avatar"> <span>{{$user->name}}</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{$site . 'profile'}}"><i class="lnr lnr-user"></i> <span>{{$dict->myProfile}}</span></a></li>
              
                            <li><a href="{{$site . 'logout'}}"><i class="lnr lnr-exit"></i> <span>{{$dict->logout}}</span></a></li>
                        </ul>
                    </li>
        
                </ul>
            </div>
            
            <div id="navbar-menu">
                <ul class="nav navbar-nav navbar-right">
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="{{flag($l = Lang::get())}}" alt="{{$l}}"> <span>{{Lang::get()}}</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            @foreach( $languages as $lang ):

                                @if( $lang !== $l ):
                                    <li>
                                        <a href="{{$site . 'home/setlang/' . $lang}}">
                                            <img src="{{flag($lang)}}" alt="{{$l}}"> <span>{{$lang}}</span>
                                        </a>
                                    </li>
                                @endif:

                            @endforeach:
                        </ul>
                    </li>
        
                </ul>
            </div>
        </div>
    </nav>
    <!-- END NAVBAR -->
    <!-- LEFT SIDEBAR -->
    <div id="sidebar-nav" class="sidebar">
        <div class="sidebar-scroll">
            @View::get('sections/menus'):
        </div>
    </div>
    <!-- END LEFT SIDEBAR -->