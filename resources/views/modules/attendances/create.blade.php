@extends('lapdash::master')

@section('adminlte_css')
@stop

@section('classes_body'){{ 'login-page' }}@stop

@section('body')
    <div class="login-box">
        <div class="card">
            <div class="card-header bg-gray">
                <h3 class="card-title float-none text-center">
                    <div class="attendance-logo">

                        {{-- Logo Image --}}
                        @if (config('adminlte.auth_logo.enabled', false))
                            <img src="{{ asset(config('adminlte.auth_logo.img.path')) }}"
                                alt="{{ config('adminlte.auth_logo.img.alt') }}"
                                @if (config('adminlte.auth_logo.img.class', null))
                                    class="{{ config('adminlte.auth_logo.img.class') }}"
                                @endif
                                @if (config('adminlte.auth_logo.img.width', null))
                                    width="{{ config('adminlte.auth_logo.img.width') }}"
                                @endif
                                @if (config('adminlte.auth_logo.img.height', null))
                                    height="{{ config('adminlte.auth_logo.img.height') }}"
                                @endif>
                        @else
                            <img src="{{ asset(config('adminlte.logo_img')) }}"
                                alt="{{ config('adminlte.logo_img_alt') }}" height="50">
                        @endif
                        &nbsp;&nbsp;
                        {{-- Logo Label --}}
                        <div style="font-size:1.6em; display:inline-block;"><strong>{!! config('adminlte.title') !!}</strong></div>
                    </div>
                </h3>
            </div>

            {{-- Card Body --}}
            <div class="card-body attendance-card-body ">
                <div class="row">
                    <div class="col-12">
                        <strong><span id="time" class="float-right">{{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('d/m/Y h:i:s A') }}</span></strong>
                    </div>
                </div>
                &nbsp;                
                @include('flash::message')
                <form class="form" action="{{ route('welcome.store') }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <input type="text" name="people_no" class="form-control @error('people_no') is-invalid @enderror" value="{{ old('people_no') }}" placeholder="Staff No" autofocus >
                        @error('people_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" name="clock_in" value="in" class="btn btn-block btn-flat bg-success">In</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" name="clock_out" value="out" class="btn btn-block btn-flat bg-danger">Out</button>
                        </div>
                    </div>
                </form>
                <br>
                <div class="row">
                    <div class="col-12 text-center"><a href="{{ route('login') }}">Management Login</a></div>
                </div>
            </div>

            {{-- Card Footer --}}
            <div class="card-footer">
                <div class="text-center">
                    <span><strong>{{ date('Y') .' '.config('app.name') }}</strong> by <a href="https://mushmero.com" target="_blank">Mushmero</a></span>
                    <br>
                    <div class="text-xs">Version {{ Mushmero\Lapdash\Helpers\Helper::appVer() }}</div>
                </div>
            </div>

        </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop