
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}"> <span>Sea-Watch.app</span><br>Safe-Passage</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            
            <ul class="nav navbar-nav navbar-left">
                
                @if (Request::is('/')||Request::is('map')||Request::is('vehicleGrid'))
                <li>
                    
                    <a href="{{ URL::to('/') }}" class="{{ (Request::is('/') ? 'active' : '') }}">
                        <span>Mode</span><br />
                        <i class="zmdi zmdi-map"></i> Cases</a></li>
                    <li>
                        
                    <a href="{{ URL::to('map') }}" class="{{ (Request::is('map') ? 'active' : '') }}">
                        <span> </span><br />
                        <i class="zmdi zmdi-view-module"></i> Map</a>
                    </li>
                
                <li><a href="#" id="createCase"><span>Create</span><br>New Case</a></li>
                @endif
                
                
                {{--<li class="{{ (Request::is('articles') ? 'active' : '') }}">
                    <a href="{{ URL::to('articles') }}">Articles</a>
                </li>
                <li class="{{ (Request::is('about') ? 'active' : '') }}">
                    <a href="{{ URL::to('about') }}">About</a>
                </li>
                <li class="{{ (Request::is('contact') ? 'active' : '') }}">
                    <a href="{{ URL::to('contact') }}">Contact</a>
                </li>--}}
          </ul>
          <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li class="{{ (Request::is('auth/login') ? 'active' : '') }}">


                        <a href="{{ URL::to('auth/login') }}">  <span>Account</span><br />Login</a>
                    </li>
                @else
                   
                     <li id="online_status">
                        <a>
                            <div class="status_icon">
                                <div class="dot"></div>
                                <div class="pulse"></div>
                            </div>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><span>{{ Auth::user()->organisation }}</span><br />{{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu" role="menu">
                            @if(Auth::check())
                                @if(Auth::user()->admin==1)
                                    <li>
                                        <a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-tachometer"></i> Admin Dashboard</a>
                                    </li>
                                @endif
                                <li role="presentation" class="divider"></li>
                            @endif
                            <li>
                                <a href="{{ URL::to('auth/logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                @endif
                
                    <!--<li class="dropdown alert_dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            <i class="zmdi zmdi-notifications mdc-text-white"></i>
                            <!--<i class="fa fa-caret-down"></i>-->
                        <!--</a>
                        <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#"><i class="fa fa-tachometer"></i>New Cases</a>
                                    </li>
                        </ul>
                    </li>-->
          </ul>
        </div>
      </div>
    </nav>

                @include('cases.create')