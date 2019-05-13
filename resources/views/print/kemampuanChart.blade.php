<!DOCTYPE html>
<html>
<head>
	<title>Chart</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div style="position: relative; height:40vh; width:80vw">
    {!! $chart->container() !!}
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    {!! $chart->script() !!}
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
     <script>
         chart = document.getElementById('{{$chart->id}}');
         console.log(chart);
         chart.parentNode.style.height = '300px';
         chart.parentNode.style.width = '350px';
         chart.onanimationend = function() {
            setTimeout(function() {            
                var urlBase64=chart.toDataURL();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ url('/chart/save') }}",
                    data: {
                        id: {{$id_mahasiswa}},
                        img: urlBase64,
                    }
                }).done(function(o){
                    console.log("saved")
                });
            }, 1000);  
         }      
     </script> 
</body>
</html>