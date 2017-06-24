<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">

        <div style="float:left; margin-left:10px; margin-top:8px;">
        @@Html::image(FILES_DIR . 'ico.png'):
        </div>
        <a class="navbar-brand" href="@@siteUrl():"><span style="color:/#2C5072">DEV</span> <span style="color:/#00BFFF">TOOLS</span></a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book"></i> @@SELECT_PROJECT: <b class="caret"></b></a>
            <ul class="dropdown-menu">
                @foreach( PROJECT_LIST as $project ):
                    @if($project !== SELECT_PROJECT):
                    <li>
                        <a href="@@siteUrl('home/project/' . $project):"> @$project:</a>
                    </li>
                    @endif:
                @endforeach:
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-font"></i> @@EDITOR_THEMES[SELECT_EDITOR_THEME]: <b class="caret"></b></a>
            <ul class="dropdown-menu">
                @foreach( EDITOR_THEMES as $key => $theme ):
                    @if($theme !== SELECT_EDITOR_THEME):
                    <li style="width:200px">
                        <a href="@@siteUrl('home/editorTheme/' . $key):"> @$theme:</a>
                    </li>
                    @endif:
                @endforeach:
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag"></i>  @$upperLang = strtoupper(getLang()): <b class="caret"></b></a>
            <ul class="dropdown-menu">
                @foreach( LANGUAGES as $lang ):
                    @if($lang !== $upperLang):
                    <li>
                        <a href="@@siteUrl('home/lang/' . strtolower($lang)):"> @$lang:</a>
                    </li>
                    @endif:
                @endforeach:
            </ul>
        </li>

        @if( Session::select('isLogin') ):
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>  @@Session::select('username'): <b class="caret"></b></a>
            <ul class="dropdown-menu">

                    <li>
                        <a href="@@siteUrl('login/out'):"> Logout</a>
                    </li>

            </ul>
        </li>
        @endif:
    </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            @foreach( MENUS as $menu => $attr ):
            <li class="{{$attr['href'] === CURRENT_CFPATH ? 'active' : ''}}">
                <a href="@@siteUrl($attr['href']):"{{isset($attr['target']) ? 'target="' . $attr['target'] . '"' : ''}}>
                    <i class="fa fa-fw fa-@$attr['icon']:"></i>
                    {{LANG[$menu]}} {{isset($attr['badge']) ? '<span class="badge label-success">' . $attr['badge'] . '</span>' : NULL}}
                </a>
            </li>
            @endforeach:
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>
