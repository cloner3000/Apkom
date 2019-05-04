<!DOCTYPE html>
<html>
<head>
	<title>SKPI Report</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="{{asset('css/pure.min.css')}}">
	<style>
	.stretch{
		width:100%;
		margin-top: 5px;
	}
	h2{
		text-align: center;
		margin-bottom: 1px;
		margin-top: 1px;
	}
	.line{
		border-bottom: 2px solid #000;
		padding-bottom: 10px;
	}
	.head{
	margin-bottom: auto;
	}
	</style>
</head>
<body>
	<div class="pure-g">
		<div class="head pure-u-1 pure-u-md-1-3">
			<img class="pure-img" src="{{asset('img/logo-large.png')}}">
		</div>
		<div class="pure-u-1 pure-u-md-2-3 line">
			<h2>APLIKASI KOMPETENSI MAHASISWA</h2>
			<h2>UNIVERSITAS KATOLIK DARMA CENDIKA</h2>
			<h2>SKPI List Report</h2>
		</div>
	</div>
	<table class='pure-table stretch'>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
                <th>Npm</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>Point Skpi</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@foreach($skpi as $data)
			<tr @if ($i%2 == 1) class="pure-table-odd" @endif>
				<td>{{ $i++ }}</td>
				<td>{{$data->nama}}</td>
                <td>{{$data->npm}}</td>
				<td>{{$data->nama_jurusan}}</td>
                <td>{{$data->status}}</td>
				<td>{{$data->point_skpi}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	

</body>
</html>