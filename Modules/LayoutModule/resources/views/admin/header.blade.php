<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
    <?php //print_r(auth()->user()->privileges_keys());
    ?>
    <div class="navbar-wrapper">
        {{-- <div class="navbar-header"> --}}
        <div class="row">
            <div class="col-xl-3 col-lg-2 col-xs-0">
                @if (Auth::guard('user')->check())
                    <div class="col-xl-12 col-lg-12 col-xs-12 header-branch">
                        <span>
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                @elseif(Auth::guard('admin')->check())
                    <div class="col-xl-12 col-lg-12 col-xs-12 header-branch">
                        <span>
                            مدير البرنامج
                        </span>
                    </div>
                @endif
            </div>
            <div class="col-xl-6 col-lg-8 col-xs-6 h-logo">
                {{-- <a href="{{url('/')}}" class=" nav-link" target="_blank"> --}}
                <img src="{{ url('assets/images/logo.png') }}" class="logo">
                {{-- </a> --}}
            </div>
            <div class="col-xl-3 col-lg-2 col-xs-6 h-info">
                <ul class="nav navbar-nav float-xs-right" style="margin-left: 63px">
                    <li class="dropdown dropdown-user nav-item header-user">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                            <span class="avatar avatar-online">
                            </span>
                            <span class="user-name">{{ auth()->user()->name }}</span>
                        </a>
                        <div class="dropdown-menu arrow dropdown-menu-left">
                            <a href="{{ route(Auth::getDefaultDriver() . '.changePassword') }}" class="dropdown-item">
                                <i class="icon-key4"></i>
                                تغيير كلمة المرور
                            </a>
                            <a href="{{ route(Auth::getDefaultDriver() . '.logout') }}" class="dropdown-item">
                                <i class="icon-power3"></i>
                                خروج
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>








    </div>
</nav>
