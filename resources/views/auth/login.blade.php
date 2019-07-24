@extends('layouts.app')

@section('content')
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body">
          <a href="{{ route('welcome') }}"><img class="mx-auto d-block mb-3" src="{{asset('img/logo.png')}}" alt="Logo UKDC"></a>
        <h3 class="login-box-msg">Sistem Informasi <b>SKPI</b></h3>
        <form action="{{ route('login') }}" method="post">
          @csrf
            <div class="input-group mb-3">
              <input id="email" placeholder="Email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
              <div class="input-group-append">
                  <span class="fa fa-envelope input-group-text"></span>
              </div>
              @if ($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
            </div>
            <div class="input-group mb-3">
              <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
              <div class="input-group-append">
                  <span class="fa fa-lock input-group-text"></span>
              </div>
              @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
              @endif
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">
                    {{ __('Remember') }}
                  </label>
              </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
  <p class="text-center text-white">Copyright &copy; 2019 <a href="http://ukdc.ac.id/">UKDC</a>. All rights reserved</p>
@endsection