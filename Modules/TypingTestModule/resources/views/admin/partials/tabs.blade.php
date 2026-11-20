@php $guard = Auth::getDefaultDriver(); @endphp
<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link @if (Request::routeIs($guard . '.typing.results.*')) active @endif"
            href="{{ route($guard . '.typing.results.index') }}">
            <i class="fa fa-bar-chart"></i> النتائج
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (Request::routeIs($guard . '.typing.texts.*')) active @endif"
            href="{{ route($guard . '.typing.texts.index') }}">
            <i class="fa fa-file-text-o"></i> بنك النصوص
        </a>
    </li>
    @if ($guard === 'admin')
        <li class="nav-item">
            <a class="nav-link @if (Request::routeIs('admin.typing.settings.*')) active @endif"
                href="{{ route('admin.typing.settings.edit') }}">
                <i class="fa fa-cog"></i> الإعدادات
            </a>
        </li>
    @endif
</ul>
