<?php

namespace Apkom;

use Apkom\Mahasiswa;
use Apkom\Kompetensi;
use Apkom\BidangKompetensi;
use Apkom\BuktiKompetensiWajib;
use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\SkpiResource;
use Apkom\Charts\KemampuanChart;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Jenssegers\Date\Date;
use PDF;

class Skpi extends Model
{
    protected $table = 'skpi';

    protected $fillable = [
        'id_mahasiswa',
        'status',
        'point_skpi',
        'file'
    ];

    public function getData($id=false){
        if($id){    
            $skpi = self::join('mahasiswa', function($join) use($id){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->where('jurusan.id_account', $id)
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.*','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->orderByRaw("FIELD(status, 'progress', 'published') ASC")
            ->latest()
            ->paginate(8);
        }else{    
            $skpi = self::join('mahasiswa', function($join){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.*','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->orderByRaw("FIELD(status, 'progress', 'published') ASC")
            ->latest()
            ->paginate(8);
        }
        return SkpiResource::collection($skpi);     
    }

    public function getDataReport($id=false){
        if($id){    
            $skpi = self::join('mahasiswa', function($join) use ($id){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->where('jurusan.id_account', $id)
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.status','skpi.point_skpi','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->get();
        }else{    
            $skpi = self::join('mahasiswa', function($join){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.status','skpi.point_skpi','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->get();
        }
        return SkpiResource::collection($skpi);
        
    }    

    public function searchData($search, $id=false){
        if($id){    
            $skpi = self::join('mahasiswa', function($join) use($id){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->where('jurusan.id_account', $id)
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.*','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->where('nama','LIKE',"%$search%")
            ->orWhere('npm','LIKE',"%$search%")
            ->paginate(8);
        }else{    
            $skpi = self::join('mahasiswa', function($join){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.*','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->where('nama','LIKE',"%$search%")
            ->orWhere('npm','LIKE',"%$search%")
            ->paginate(8);
        }
        return SkpiResource::collection($skpi);
    }

    public function saveData(){
        $check = $this->checkStatus();
        if($check['status'] == 'progress' or $check['status'] == 'published' ){
            return ['message' => 'You have skpi'];
        }
        $skpi = new Skpi;
        $skpi->id_mahasiswa = auth('api')->user()->mahasiswa()->id;
        if($skpi->save()){
            return ['message' => 'Save Skpi successfully'];
        }else{
            return ['message' => 'Save Skpi failed'];
        }
    }

    public function deleteData($id){
        $skpi = self::find($id);
        if($skpi->delete()){
            $fileTemp = public_path('storage/data/skpi/').$skpi->file;
            if($user->file != '' && file_exists($fileTemp)){
                @unlink($fileTemp);
            }
            return ['message' => 'Delete Skpi Successfull'];
        }else{
            return ['message' => 'Delete Skpi Failed'];
        }
    }

    public function publish($id){
        $skpi = self::find($id);
        if($skpi->mahasiswa()->first()->jurusan()->first()->user()->first()->id == auth('api')->user()->id){
            $pdf = $this->generatedSkpi($skpi->id_mahasiswa);
            $filePdf = PDF::loadView('print.skpi', ['skpi' => $pdf]);
            $file = $filePdf->download()->getOriginalContent();
            $fileName = time().uniqid().'.pdf';
            Storage::disk('skpi')->put($fileName, $file);
            $skpi->file = $fileName;
            $skpi->point_skpi = $this->calculateSkpiPoint($skpi->id_mahasiswa);
            $skpi->status = 'published';
            if($skpi->save()){
                return ['message' => 'Publish Skpi successfully'];
            }else{
                return ['message' => 'Publish Skpi failed'];
            }
        }else{
            return ['message' => 'You dont have access to this skpi'];
        }    
    }

    public function checkStatus(){
        $mahasiswa = Mahasiswa::where('id_account', auth('api')->user()->id)->first();
        if($mahasiswa != null){
            $id_mahasiswa = auth('api')->user()->mahasiswa()->id;
            $kompetensi = Kompetensi::where('id_mahasiswa', $id_mahasiswa)->first();
            if($kompetensi != null){
                $skpi = self::where('id_mahasiswa', $id_mahasiswa)->first();
                if($skpi != null){
                    if($skpi->status == 'progress'){
                        return ['status' => 'progress'];
                    }else{
                        return ['status' => 'published'];
                    }
                }else{
                    return ['status' => 'null'];
                }   
            }else{
                return ['status' => 'uncompleted'];
            }
        }else{
            return ['status' => 'unregister'];
        }
    }

    public function mahasiswa(){
        return $this->hasOne('Apkom\Mahasiswa', 'id', 'id_mahasiswa');
    }

    public function generatedSkpi($id_mahasiswa){
        $kompetensi = Kompetensi::join('bidang_kompetensi', function($join){
            $join->on('kompetensi.id_bidang','=','bidang_kompetensi.id')
            ->select('nama_bidang', 'nama_bidang_en');
        })
        ->where('id_mahasiswa', $id_mahasiswa)
        ->where('active', 1)
        ->get()
        ->groupBy('nama_bidang');

        $mahasiswa = Mahasiswa::where('id', $id_mahasiswa)->with('jurusan')->first();
        $tgl_masuk = new Date($mahasiswa->tgl_masuk);
        $tgl_lulus = new Date($mahasiswa->tgl_lulus);
        $mahasiswa->tgl_masuk_en = $tgl_masuk->format('F j Y');
        $mahasiswa->tgl_lulus_en = $tgl_lulus->format('F j Y');
        Date::setLocale('id');
        $mahasiswa->tgl_masuk = $tgl_masuk->format('j F Y');
        $mahasiswa->tgl_lulus = $tgl_lulus->format('j F Y');

        $kompetensiWajib = BuktiKompetensiWajib::where('id_mahasiswa', $id_mahasiswa)
        ->whereNotNull('bukti_wajib')
        ->select('nama_kompetensi_wajib')
        ->get();

        $kemampuan = Mahasiswa::find($id_mahasiswa)
        ->kemampuan()
        ->selectRaw('count(nama_kemampuan) as jumlah, nama_kemampuan')
        ->groupBy('nama_kemampuan')
        ->orderBy('jumlah', 'DESC')
        ->limit(5)
        ->get();

        return [
            'kompetensi' => $kompetensi, 
            'mahasiswa' => $mahasiswa, 
            'kompetensiWajib' => $kompetensiWajib,
            'kemampuan' => $kemampuan];
    }

    public function calculateSkpiPoint($id_mahasiswa){
        $kompetensi = Kompetensi::where('id_mahasiswa', $id_mahasiswa)
        ->selectRaw('sum(point_kompetensi) as total_point, count(id) as jumlah_kompetensi')
        ->first();
        return $kompetensi->total_point/$kompetensi->jumlah_kompetensi;

    }

    public function saveChart($request){
        $img = Image::make($request->img);
        $img->save(public_path('storage/data/chart/').$request->id.'.png');
        return 'Success';
    }

    public function chart($id_mahasiswa){
        $kemampuan = Mahasiswa::find($id_mahasiswa)
        ->kemampuan()
        ->selectRaw('count(nama_kemampuan) as jumlah, nama_kemampuan')
        ->groupBy('nama_kemampuan')
        ->orderBy('jumlah', 'DESC')
        ->get();
        $nama_kemampuan=collect([]);
        $jumlah=collect([]);
        $i =1;
        foreach($kemampuan as $key => $value){
            $nama_kemampuan->push($value->nama_kemampuan);
            $jumlah->push($value->jumlah);
            if($i == 5) break;
            $i++;
        }
        $chart = new KemampuanChart;
        $chart->labels($nama_kemampuan);
        $chart->dataset('The most owned soft skill', 'pie', $jumlah)
        ->backgroundColor([
            '#FF3D67',
            '#36A2EB',
            '#FFC233',
            '#22FF22',
            '#990099'
            ]);    
        return view('print.kemampuanChart', compact('chart','id_mahasiswa'));
    }
}
