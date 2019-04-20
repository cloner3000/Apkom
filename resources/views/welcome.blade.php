@extends('layouts.app')

@section('content')
  <div class="login-box">
      <div class="card">
        <div class="card-body login-card-body">
          <img class="mx-auto d-block mb-3" src="{{asset('img/logo.png')}}" alt="Logo UKDC">
          <h3 class="login-box-msg">Aplikasi <b>Kompetensi</b></h3>
        </div>
        <div class="container">
            <div class="row">
              <div class="col-md-6">
                  <button class="btn btn-primary">Validation</button>
              </div>
              <div class="col-md-6">
                  <button class="btn btn-primary">Validation</button>
              </div>
            </div>
          </div>
      </div>
  </div>
  <p class="text-center text-white">Copyright &copy; 2019 <a href="http://ukdc.ac.id/">UKDC</a>. All rights reserved</p>
@endsection
      