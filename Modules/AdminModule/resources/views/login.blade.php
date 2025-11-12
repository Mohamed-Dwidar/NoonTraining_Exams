@extends('layoutmodule::admin.login')

@section('content')

<div class="card border-grey border-lighten-3 m-0">
    <div class="card-header no-border">
        <div class="card-title text-xs-center">
            <div class="p-1">
                <a href="{{url('/')}}">
                    <img src="{{url('/assets/images/logo.png')}}" alt="main logo" style="width: 100px;">
                </a>
            </div>
        </div>
        <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
            <span>برنامج الحسابات</span></h6>
            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
                <span>تسجيل دخول مدير البرنامج</span></h6>
    </div>
    <div class="card-body collapse in">
        <div class="card-block">
            @isset($url)
            <form method="POST" action='{{ url("$url/login") }}' id="form-login">
                @else
                <form method="POST" action="{{ route('admin.loginpost') }}" aria-adminel="{{ __('Login') }}">
                    @endisset
                    @csrf

                    @if ($errors->any())
                    <div class="alert alert-warning mb-2" role="alert">
                        <strong>خطـأ!</strong>
                        {{$errors->first()}}
                    </div>
                    @endif
                    <fieldset class="form-group position-relative has-icon-left mb-0">
                        <input type="text" class="form-control form-control-lg input-lg" name="email" id="email"
                            value="{{ old('email') }}" placeholder="البريد الألكتروني" required>
                        <div class="form-control-position">
                            <i class="icon-head"></i>
                        </div>
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </fieldset>
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" class="form-control form-control-lg input-lg" id="user-password"
                            name="password" placeholder="كلمة المرور" required>
                        <div class="form-control-position">
                            <i class="icon-key3"></i>
                        </div>
                    </fieldset>
                    <fieldset class="form-group row">
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                            {{-- <fieldset>
                                <input type="checkbox" id="remember-me" class="chk-remember">
                                <label for="remember-me"> Remember Me</label>
                            </fieldset> --}}
                        </div>
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-right">
                            {{-- <a href="recover-password.html" class="card-link">Forgot Password?</a> --}}
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="icon-unlock2"></i>
                        {{ __('دخول') }}
                    </button>
                </form>
        </div>
    </div>
</div>
@endsection