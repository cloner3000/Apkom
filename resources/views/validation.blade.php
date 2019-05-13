@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card mt-4">
          <div class="card-body">
            <img class="mx-auto d-block mb-3" src="{{asset('img/logo.png')}}" alt="Logo UKDC">
            <h3 class="login-box-msg">Aplikasi <b>Kompetensi</b></h3>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="container">
                  <h5 class="text-center">Scan QR Code here</h5>
                  <video id="preview" class="qr-validation bg-light p-3"></video>
              </div>
            </div>
            <div class="col-md-8">
              <div class="container">
                  <h5 class="text-center">SKPI Information</h5>
                  <div class="bg-light">
                    <p>This SKPI Has Valid</p>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </div>  
  </div>
</div>  
  <p class="text-center text-white">Copyright &copy; 2019 <a href="http://ukdc.ac.id/">UKDC</a>. All rights reserved</p>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function (content) {
      alert(content);
    });
    Instascan.Camera.getCameras().then(function (cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function (e) {
      console.error(e);
    });
  </script>    
@endsection
