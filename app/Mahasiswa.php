<?php

namespace Apkom;

use Apkom\BuktiKompetensiWajib;
use Apkom\KompetensiWajib;
use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\MahasiswaResource;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'id_jurusan',
        'npm',
        'nama',
        'kota_lahir',
        'tgl_lahir',
        'no_ijazah',
        'tgl_masuk',
        'tgl_lulus',
        'ipk'
    ];

    protected $hidden = [
        'id_account'
    ];

    public function getData(){
        $mahasiswa = self::with('jurusan')->latest()->paginate(8);
        return MahasiswaResource::collection($mahasiswa);
    }

    public function getDataReport(){
        $mahasiswa = self::with('jurusan')->get();
        return MahasiswaResource::collection($mahasiswa);
    }

    public function searchData($search){
        $mahasiswa = self::with('jurusan')
        ->where('nama','LIKE',"%$search%")
        ->orWhere('npm','LIKE',"%$search%")
        ->orWhere('no_ijazah','LIKE',"%$search%")
        ->paginate(8);
        return MahasiswaResource::collection($mahasiswa);
    }

    public function saveData($request, $id=false){
        $storeKompetensiWajib = true;
        $mahasiswa = new Mahasiswa;
        if($id){
            $mahasiswa = self::find($id);
            if($mahasiswa->id_account != auth('api')->user()->id){
                return ['message' => 'Mahasiswa Not Found'];
            }
            if($mahasiswa->id_jurusan != $request->id_jurusan){
                $mahasiswa->buktiKompetensiWajib()->delete();
            }else{
                $storeKompetensiWajib = false;
            }
        }
        $mahasiswa->id_jurusan = $request->id_jurusan;
        $mahasiswa->id_account = auth('api')->user()->id;
        $mahasiswa->npm = $request->npm;
        $mahasiswa->nama = $request->nama;
        $mahasiswa->kota_lahir = $request->kota_lahir;
        $mahasiswa->tgl_lahir = $request->tgl_lahir;
        $mahasiswa->no_ijazah = $request->no_ijazah;
        $mahasiswa->tgl_masuk = $request->tgl_masuk;
        $mahasiswa->tgl_lulus = $request->tgl_lulus;
        $mahasiswa->ipk = $request->ipk;
        if($mahasiswa->save()){
            if($storeKompetensiWajib){
                $this->storeKompetensiWajib($mahasiswa);
                return ['message' => 'Save Mahasiswa Successfull'];
            }else{
                return ['message' => 'Save Mahasiswa Successfull not store kompetensi wajib'];
            }
        }else{
            return ['message' => 'Save Mahasiswa Failed'];
        }
    }

    public function deleteData($id){
        $mahasiswa = self::find($id);
        if($mahasiswa->delete()){
            return ['message' => 'Delete Mahasiswa Successfull'];
        }else{
            return ['message' => 'Delete Mahasiswa Failed'];
        }
    }

    public function getMahasiswaProfile(){
        $mahasiswa = self::with('jurusan')->where('id_account', auth('api')->user()->id)->first();
        if($mahasiswa != null){
            return $mahasiswa;
        }else{
            return ['message' => 'Mahasiswa Not Found'];
        }
    }

    public function storeKompetensiWajib($mahasiswa){
        $kompetensiWajib = KompetensiWajib::where('id_jurusan', $mahasiswa->id_jurusan)->select('nama_kompetensi_wajib')->get();
        foreach($kompetensiWajib as $kompetensi){
            $buktiKompetensi = new BuktiKompetensiWajib;
            $buktiKompetensi->id_mahasiswa = $mahasiswa->id;
            $buktiKompetensi->nama_kompetensi_wajib = $kompetensi['nama_kompetensi_wajib'];
            $buktiKompetensi->save();
        }
        return true;
    }

    public function jurusan(){
        return $this->belongsTo('Apkom\Jurusan', 'id_jurusan');
    }

    public function buktiKompetensiWajib(){
        return $this->hasMany('Apkom\BuktiKompetensiWajib', 'id_mahasiswa');
    }

    public function skpi(){
        return $this->hasOne('Apkom\Skpi', 'id_mahasiswa', 'id');
    }

    public function kompetensi(){
        return $this->hasMany('Apkom\Kompetensi', 'id_mahasiswa');
    }

    public function user(){
        return $this->hasOne('Apkom\User', 'id', 'id_account');
    }
}
