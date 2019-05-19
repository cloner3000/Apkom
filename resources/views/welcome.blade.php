@extends('layouts.app')

@section('content')
  <div class="login-box">
      <div class="card">
        <div class="card-body login-card-body">
          <a href="{{ route('welcome') }}"><img class="mx-auto d-block mb-3" src="{{asset('img/logo.png')}}" alt="Logo UKDC"></a>
          <h3 class="login-box-msg">Aplikasi <b>Kompetensi</b></h3>
          <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                  <a class="btn btn-outline-primary btn-block" href="{{ route('login') }}">Login</a>
                  </div>
                  <div class="col-md-6">
                  <a class="btn btn-outline-primary btn-block" href="{{ route('register') }}">Register</a>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-12">
                  <a class="btn btn-outline-success btn-block" href="{{ route('validation') }}">Validation</a>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
  </div>
  <p class="text-center text-white">Copyright &copy; 2019 <a href="http://ukdc.ac.id/">UKDC</a>. All rights reserved</p>
@endsection
