@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card mt-4">
          <div class="card-body">
              <a href="{{ route('welcome') }}"><img class="mx-auto d-block mb-3" src="{{asset('img/logo.png')}}" alt="Logo UKDC"></a>
            <h3 class="login-box-msg">Aplikasi <b>Kompetensi</b></h3>
          </div>
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="container text-center">
                <h5>Scan QR Code here</h5>
                <div class="bg-light p-3">
                    <button id="scan" class="btn btn-outline-success mt-5 mb-5">Scann Now</button>
                    <video id="preview" class="qr-validation hide"></video>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="container">
                  <h5 class="text-center">SKPI Information</h5>
                  <div id="information" class="bg-light p-3">
                    <div>
                      Name :<br>
                      ---------------<br>
                      Number of Certificate :<br>
                      ---------------<br>
                      Grade Point Average :<br>
                      ---------------<br>
                    </div>
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
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/instascan.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(() => {
    $('#scan').click(() => {
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', (content) => {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "POST",
          url: "{{ url('/check') }}",
          data: {
              id: content
          },
          success: (response) => {
            $('#information').empty();
            if(response.data != null){
              $('#information').append(
                '<h6 class="d-inline float-right">Verified <i class="fas fa-check-circle text-success"></i></h6>'+
                '<div> Name :<br><strong>'+response.data.nama+
                '</strong><br> Number of Certificate :<br><strong>'+response.data.no_ijazah+
                '</strong><br> Grade Average Point :<br><strong>'+response.data.ipk+'</strong></div>'
              );
            }else{
              $('#information').append('<h2 class="text-center mt-4"><i class="fas fa-window-close text-danger"></i></h2><h5 class="text-center mb-5">SKPI not Verified</h5>');
            }
          }
        });
        scanner.stop().then(() => {
          $('#scan').show();
          $('#preview').addClass('hide');
        });
      });
      Instascan.Camera.getCameras().then((cameras) => {
        if (cameras.length > 0) {
          scanner.start(cameras[0]).then(() => {
            $('#scan').hide();
            $('#preview').removeClass('hide');
          });
        } else {
          console.error('No cameras found.');
        }
      }).catch((e) => {
        console.error(e);
      });  
  });
});
</script>   
@endsection
