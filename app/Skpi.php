<?php

namespace Apkom;

use Apkom\Mahasiswa;
use Apkom\Kompetensi;
use Apkom\BuktiKompetensiWajib;
use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\SkpiResource;

class Skpi extends Model
{
    protected $table = 'skpi';

    protected $fillable = [
        'id_mahasiswa',
        'status',
        'point_skpi',
        'qr_code'
    ];

    public function getData(){
        $skpi = self::join('mahasiswa', function($join){
            $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
            ->select('jurusan.nama_jurusan');
        })
        ->select('skpi.*','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
        ->orderByRaw("FIELD(status, 'progress', 'published') ASC")
        ->paginate(8);
        return SkpiResource::collection($skpi);
        
    }

    public function getDataReport(){
        $skpi = self::join('mahasiswa', function($join){
            $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
            ->select('jurusan.nama_jurusan');
        })
        ->select('skpi.status','skpi.point_skpi','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
        ->get();
        return SkpiResource::collection($skpi);
        
    }    

    public function searchData($search){
        $skpi = self::join('mahasiswa', function($join){
            $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
            ->select('jurusan.nama_jurusan');
        })
        ->select('skpi.*','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
        ->where('nama','LIKE',"%$search%")
        ->orWhere('npm','LIKE',"%$search%")
        ->paginate(8);
        return SkpiResource::collection($skpi);
    }

    public function saveData($id=false){
        $check = $this->checkStatus();
        if($check['status'] == 'progress' or $check['status'] == 'published' ){
            return ['message' => 'You have skpi'];
        }
        $skpi = new Skpi;
        if($id){
            $skpi = self::find($id);
            $skpi->status = $request->status;
        }
        $skpi->id_mahasiswa = auth('api')->user()->mahasiswa()->id;
        $skpi->qr_code = time().uniqid();
        if($skpi->save()){
            return ['message' => 'Save Skpi successfully'];
        }else{
            return ['message' => 'Save Skpi failed'];
        }
    }

    public function deleteData($id){
        $skpi = self::find($id);
        if($skpi->delete()){
            return ['message' => 'Delete Skpi Successfull'];
        }else{
            return ['message' => 'Delete Skpi Failed'];
        }
    }

    public function publish($id){
        $skpi = self::find($id);
        $skpi->status = 'published';
        if($skpi->save()){
            return ['message' => 'Publish Skpi successfully'];
        }else{
            return ['message' => 'Publish Skpi failed'];
        }
    }

    public function checkStatus(){
        $mahasiswa = Mahasiswa::where('id_account', auth('api')->user()->id)->first();
        if($mahasiswa != null){
            $id_mahasiswa = auth('api')->user()->mahasiswa()->id;
            $kompetensi = Kompetensi::where('id_mahasiswa', $id_mahasiswa)->first();
            $buktiKompetensiWajib = BuktiKompetensiWajib::where('id_mahasiswa', $id_mahasiswa)->first();
            if($kompetensi != null and $buktiKompetensiWajib != null){
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
            return ['status' => 'uncompleted'];
        }
    }

    public function mahasiswa(){
        return $this->hasOne('Apkom\Mahasiswa', 'id', 'id_mahasiswa');
    }
}