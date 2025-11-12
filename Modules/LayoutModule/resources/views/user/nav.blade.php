<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col nav-links">
        <nav id="primary-menu">
            <ul class="top-menu menu-eff text-right">

                <li>
                    <a href="{{ route(Auth::getDefaultDriver() . '.courses') }}"
                        class="@if (Request::segment(2) == 'courses' && in_array(Request::segment(3), ['', 'add', 'edit', 'show'])) active @endif">
                        <i class="fa fa-desktop"></i>
                        <span>الدورات</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.students') }}"
                        class="@if (Request::segment(2) == 'students' && in_array(Request::segment(3), ['', 'add', 'edit', 'view'])) active @endif">
                        <i class="fa fa-graduation-cap"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">الطلاب</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.unknown_payment.list') }}"
                        class="@if (Request::segment(2) == 'unknown_payment') active @endif">
                        <i class="fa fa-money"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">التحويلات</span>
                    </a>
                </li>
                
                @can('show_reports')
                    <li class="nav-item">
                        <a href="{{ route(Auth::getDefaultDriver() . '.reports') }}"
                            class="@if (Request::segment(2) == 'reports') active @endif">
                            <i class="fa fa-file-text-o"></i>
                            <span data-i18n="nav.dash.main" class="menu-title">التقارير</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>

</div>
