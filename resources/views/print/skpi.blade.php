<!DOCTYPE html>
<html>
<head>
	<title>SKPI Document</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
        body{
            font-size: 12px;
            font-family: sans-serif;
        }
        @page{
            margin-top: 200px;
        }
        label{
            font-size: 10px;
        }
        input{
            font-size: 11px;
        }
        .italic{
            font-style: italic;
        }
        .bold{
            font-weight: bold;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        .firstpage{
            margin-top: -160px!important;
            page-break-after: always;
        }
        .qr{
            position: absolute;
            top: 0;
            right: 0;
        }    
        .head{
            border-bottom: 0.08rem solid #000;
            margin-bottom: 15px;
        }
        .head div img{
            margin-bottom: 10px;
        }
        .head p{
            font-size: 12px;
            margin: 2px;
        }
        .head :nth-child(4){
            margin-bottom: 20px;
        }
        .section{
            padding-top: 10px;
        }
        .section h4, h5{
            margin: 0;
            margin-bottom: 3px;
        }
        .content{
            margin: 5px 0;
            padding: 10px 0;
        }
        .wrapfield{
            position: relative;
            width: 100%;
        }
        .wrapfield .column{
            padding-top: 1px;
            display: block;
            width: 50%;
        }
        .wrapfield .left{
            margin-right: -50%;
            float: left;
        }
        .wrapfield .right{
            margin-left: 50%;
        }
        .wrapfield .left .field .label{
            width: 40%;
            margin-right: -40%;
        }
        .wrapfield .left  .field .input{
            margin-left: 40%;
            width: 60%;
        }
        .wrapfield .right .field .label{
            width: 40%;
            margin-left: 15px;
            margin-right: -59%;
        }
        .wrapfield .right  .field .input{
            margin-left: 40%;
            width: 59%;
        }
        .field{
            padding-top: 1px;
            position: relative;
            width: 100%;
            margin: 7px 0;
        }
        .field .label{
            display: block;
            width: 20%;
            margin-right: -20%;
            float: left;
        }
        .sublabel{
            font-size: 9px;
        }
        .field .input{
            display: block;
            margin-left: 20%;
            width: 80%;
        }
        .field .input input{
            width: 99%;
            padding: 6px 3px;;
        }
        .title-fix{
            position: fixed;
            top: -160px;
            padding-top: 1px;
        }
		.table{
			width: 100%;
            margin-top: 10px;
			border-collapse: collapse;
		}
		.table th, td{
			padding: 4px 2px;
			border:1px solid #4D4D4D;
		}
        .table tfoot tr :nth-child(1){
            border-right: none; 
        }
        .table tfoot tr :nth-child(2){
            border-left:none;
            border-right: none; 
        }
        .table tfoot tr :nth-child(3){
            border-left:none;
        }
		.table .header{
			background-color: #F1F1F1;
		}
        .kompetensiWajib{
            page-break-before: always;
        }
        .ttd{
            text-align: center;
            width: 30%;
            float: right;
            margin-top: 100px;
        }
        .title-ttd{
            margin-top: 10px;
            margin-bottom: 80px;
        }
	</style>
</head>
<body>
        <div class="firstpage">
            <div class="qr">
                <img src="data:img/png;base64, 
                {!! $qr = base64_encode(QrCode::format('png')
                ->size(100)->errorCorrection('H')
                ->merge('img/logo.png', 0.2, true)
                ->generate(str_replace('==',chr(rand(65,90)),base64_encode($skpi['mahasiswa']->npm)))) !!} ">
            </div>
            <div class="head text-center">
                <div>
                    <img src="{{asset('img/logo-md.png')}}">
                </div>
                <p>UNIVERSITAS KATOLIK DARMA CENDIKA</p>
                <p class="bold">SURAT KETERANGAN PENDAMPING IJAZAH</p>
                <p class="italic">DIPLOMA SUPPLEMENT</p>
            </div>
            <div class="section">
                <h4>INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI</h4>
                <h5 class="italic">INFORMATION IDENTIFYING THE HOLDER OF THE DIPLOMA SUPPLEMENT</h5>
                <div class="content">
                    <div class="field">
                        <div class="label">
                            <label class="bold">Nama Lengkap</label><br>
                            <label class="sublabel bold italic">Full Name</label>
                        </div>
                        <div class="input">
                        <input type="text" value="{{$skpi['mahasiswa']->nama}}">
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">
                            <label class="bold">Tempat, Tanggal Lahir</label><br>
                            <label class="sublabel bold italic">Place and Date of Birth</label>
                        </div>
                        <div class="input">
                            <input type="text" value="{{$skpi['mahasiswa']->kota_lahir}}, {{$skpi['mahasiswa']->tgl_lahir}}">
                        </div>
                    </div>
                    <div class="wrapfield">
                        <div class="column left">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Nomor Pokok Mahasiswa</label><br>
                                    <label class="sublabel bold italic">Student ID Number</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="{{$skpi['mahasiswa']->npm}}">
                                </div>
                            </div>
                        </div>
                        <div class="column right">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Nomor Seri Ijazah</label><br>
                                    <label class="sublabel bold italic">Number of Certificate</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="{{$skpi['mahasiswa']->no_ijazah}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section">
                <h4>INFORMASI TENTANG KUALIFIKASI INSTITUSI PENYELENGGARA</h4>
                <h5 class="italic">INFORMATION IDENTIFYING THE QUALIFICATION AND AWARDING INSTITUTION</h5>
                <div class="content">
                    <div class="wrapfield">
                        <div class="column left">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Nama Perguruan Tinggi</label><br>
                                    <label class="sublabel bold italic">Awarding Institution</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="UNIVERSITAS KATOLIK DARMA CENDIKA">
                                </div>
                            </div>
                        </div>
                        <div class="column right">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">No. Keputusan Pendirian</label><br>
                                    <label class="sublabel bold italic">Awarding Institution License</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="AKTA No.17. 13 mei 1965">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapfield">
                        <div class="column left">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Tanggal Mulai Studi</label><br>
                                    <label class="sublabel bold italic">Date of Starting Study</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="{{$skpi['mahasiswa']->tgl_masuk}}">
                                </div>
                            </div>
                        </div>
                        <div class="column right">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Tanggal Kelulusan</label><br>
                                    <label class="sublabel bold italic">Date of Graduation</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="{{$skpi['mahasiswa']->tgl_lulus}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapfield">
                        <div class="column left">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Fakultas</label><br>
                                    <label class="sublabel bold italic">Faculty of</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="{{$skpi['mahasiswa']->jurusan->fakultas}}">
                                </div>
                            </div>
                        </div>
                        <div class="column right">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Program Studi</label><br>
                                    <label class="sublabel bold italic">Department of</label>
                                </div>
                                <div class="input">
                                    <input type="text" value="{{$skpi['mahasiswa']->jurusan->nama_jurusan}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">
                            <label class="bold">Gelar yang Diberikan</label><br>
                            <label class="sublabel bold italic">Awarded Degree</label>
                        </div>
                        <div class="input">
                            <input type="text" value="{{$skpi['mahasiswa']->jurusan->gelar}}">
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">
                            <label class="bold">Bahsa Pengantar</label><br>
                            <label class="sublabel bold italic">Language of Instruction</label>
                        </div>
                        <div class="input">
                            <input type="text" value="Indonesia">
                        </div>
                    </div>
                </div>
            </div>
            <div class="section">
                <h4>INFORMASI TENTANG LEVEL KUALIFIKASI</h4>
                <h5 class="italic">INFORMATION ON THE LEVEL QUALIFICATION</h5>
                <div class="content">
                    <div class="wrapfield">
                        <div class="column left">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Jenis Pendidikan</label><br>
                                    <label class="sublabel bold italic">Type of Education</label>
                                </div>
                                <div class="input">
                                <input type="text" value="{{$skpi['mahasiswa']->jurusan->jenis_pendidikan}}">
                                </div>
                            </div>
                        </div>
                        <div class="column right">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Lama Studi</label><br>
                                    <label class="sublabel bold italic">Lenght of Programme</label>
                                </div>
                                <div class="input">
                                    @switch($skpi['mahasiswa']->jurusan->program)
                                        @case('Sarjana')
                                            <input type="text" value="8 Semester">
                                            @break
                                        @case('Diploma')
                                            <input type="text" value="8 Semester">
                                            @break
                                        @case('Magister')
                                            <input type="text" value="4 Semester">
                                            @break
                                        @case('Doktor')
                                            <input type="text" value="6 Semester">
                                            @break
                                        @case('Profesi')
                                            <input type="text" value="Belum Ditentukan">
                                            @break
                                        @case('Spesialis')
                                            <input type="text" value="Belum Ditentukan">
                                            @break            
                                        @default
                                            <input type="text" value="Belum Ditentukan">
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapfield">
                        <div class="column left">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Program</label><br>
                                    <label class="sublabel bold italic">Programme</label>
                                </div>
                                <div class="input">
                                <input type="text" value="{{$skpi['mahasiswa']->jurusan->program}}">
                                </div>
                            </div>
                        </div>
                        <div class="column right">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Sistem Penilaian</label><br>
                                    <label class="sublabel bold italic">Grading System</label>
                                </div>
                                <div class="input">
                                <input type="text" value="{{$skpi['mahasiswa']->jurusan->penilaian}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wrapfield">
                        <div class="column left">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Program Lanjutan</label><br>
                                    <label class="sublabel bold italic">Access to Future Study</label>
                                </div>
                                <div class="input">
                                    @switch($skpi['mahasiswa']->jurusan->program)
                                        @case('Sarjana')
                                            <input type="text" value="Program Master">
                                            @break
                                        @case('Diploma')
                                            <input type="text" value="Program Sarjana atau Master">
                                            @break
                                        @case('Magister')
                                            <input type="text" value="Program Doktoral">
                                            @break
                                        @case('Doktor')
                                            <input type="text" value="Belum Ditentukan">
                                            @break
                                        @case('Profesi')
                                            <input type="text" value="Belum Ditentukan">
                                            @break
                                        @case('Spesialis')
                                            <input type="text" value="Belum Ditentukan">
                                            @break            
                                        @default
                                            <input type="text" value="Belum Ditentukan">
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        <div class="column right">
                            <div class="field">
                                <div class="label">
                                    <label class="bold">Jenjang Kualifikasi</label><br>
                                    <label class="sublabel bold italic">Level of Qualification</label>
                                </div>
                                <div class="input">
                                    @switch($skpi['mahasiswa']->jurusan->program)
                                        @case('Sarjana')
                                            <input type="text" value="Level Enam">
                                            @break
                                        @case('Diploma')
                                            <input type="text" value="Level Enam">
                                            @break
                                        @case('Magister')
                                            <input type="text" value="Level Delapan">
                                            @break
                                        @case('Doktor')
                                            <input type="text" value="Level Sembilan">
                                            @break
                                        @case('Profesi')
                                            <input type="text" value="Level Tujuh / Delapan">
                                            @break
                                        @case('Spesialis')
                                            <input type="text" value="Level Delapan / Sembilan">
                                            @break            
                                        @default
                                            <input type="text" value="Belum Ditentukan">
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">
                            <label class="bold">Persyaratan Penerimaan</label><br>
                            <label class="sublabel bold italic">Entry Requirements</label>
                        </div>
                        <div class="input">
                        <input type="text" value="{{$skpi['mahasiswa']->jurusan->persyaratan}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>   
        <div class="section title-fix">
            <h3 class="bold text-center">Transkrip Sistem Partisipasi Aktivitas Mahasiswa Universitas Katolik Darma Cendika</h3>
            <div class="content">
                <div class="wrapfield">
                    <div class="column left">
                        <div class="field">
                            <div class="label">
                                <label>Nomor Pokok Mahasiswa</label><br>
                                <label class="sublabel italic">Student ID Number</label>
                            </div>
                            <div class="input">
                                <span>: {{$skpi['mahasiswa']->npm}}</span>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label">
                                <label>Nama Mahasiswa</label><br>
                                <label class="sublabel italic">Name of Student</label>
                            </div>
                            <div class="input">
                                <span>: {{$skpi['mahasiswa']->nama}}</span>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label">
                                <label>Fakultas</label><br>
                                <label class="sublabel italic">Faculty of</label>
                            </div>
                            <div class="input">
                                <span>: {{$skpi['mahasiswa']->jurusan->fakultas}}</span>
                            </div>
                        </div>
                        <div class="field">
                            <div class="label">
                                <label>Program Studi</label><br>
                                <label class="sublabel italic">Department of</label>
                            </div>
                            <div class="input">
                                <span>: {{$skpi['mahasiswa']->jurusan->nama_jurusan}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="column right text-right">
                        <img src="data:img/png;base64, 
                        {{ $qr }} ">
                    </div>
                </div>
            </div>
        </div>
        @foreach($skpi['kompetensi'] as $bidang => $kompetensi)
        <div class="section">
            <h4 class="">{{$bidang}}</h4>
            <table class="table text-center">
                <thead>
                    <tr class="header">
                        <th width="39%">Kegiatan/Activity </th>
                        <th width="22%">Tanggal/Period </th>
                        <th width="12%">Tingkat/Level </th>
                        <th width="16%">Peran/Role </th>
                        <th width="11%">Poin/Point </th>
                    </tr>
                </thead>
                <tbody>
                    @php $subTotal = 0 @endphp
                    @foreach($kompetensi as $data)
                    @php $subTotal += $data->point_kompetensi @endphp
                    <tr>
                        <td>{{$data->nama_kompetensi}}</td>
                        <td>{{$data->tgl_mulai}} - {{$data->tgl_selesai}}</td>
                        <td>{{$data->tingkat}}</td>
                        <td>{{$data->peran}}</td>
                        <td>{{$data->point_kompetensi}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>   
                        <td class="bold text-right">Sub Total :</td>
                        <td class="bold"> {{$subTotal}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endforeach  
        <div class="section kompetensiWajib">
            <h3 class="text-bold">Kompetensi Wajib</h3>
            <table class="table text-center">
                <thead>
                    <tr class="header">
                        <th width="90%">Kegiatan/Activity </th>
                        <th width="10%">Done </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($skpi['kompetensiWajib'] as $data)
                    <tr>
                        <td class="text-left">{{$data->nama_kompetensi_wajib}}</td>
                        <td>v</td>
                    </tr>    
                @endforeach  
                </tbody>
            </table>
            <div class="ttd">
                {!! 
                    Date::setLocale('id');
                    $tgl = Date::now()->format('j F Y');
                !!}
                <p>
                    {{'Surabaya, '.$tgl}}
                </p>
                <p class="title-ttd">
                    Wakil Rektor II /<br>
                    Vice Rector for Students, Alumni, and<br>
                     Partnership Affairs
                </p>
                <p>
                    Rifardi Taufiq Yufananda<br>
                    NP. 123.456.23
                </p>
            </div>
        </div>
        <script type="text/php">
            if ( isset($pdf) ) {
                $font = $fontMetrics->get_font("helvetica");
                $pdf->page_text($pdf->get_width() - 75, $pdf->get_height() - 50, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));
            }
        </script>         
</body>
</html>