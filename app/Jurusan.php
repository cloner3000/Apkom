<?php

namespace Apkom;

use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\JurusanResource;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
      'id_account',
      'nama_jurusan',
      'nama_jurusan_en',
      'program',
      'jenis_pendidikan',
      'fakultas',
      'gelar',
      'persyaratan',
      'persyaratan_en',
      'penilaian'
    ];

    public function getData(){
        $jurusan = self::with('user')->latest()->paginate(8);
        return JurusanResource::collection($jurusan);
    }

    public function getDataReport(){
        $jurusan = self::with('user')->get();
        return JurusanResource::collection($jurusan);
    }

    public function searchData($search){
        $jurusan = self::with('user')
            ->join('users', 'jurusan.id_account','=','users.id')
            ->where('nama_jurusan','LIKE',"%$search%")
            ->orWhere('name','LIKE',"%$search%")
            ->orWhere('fakultas','LIKE',"%$search%")
            ->select('jurusan.*','users.name')
            ->paginate(8);
        return JurusanResource::collection($jurusan);
    }

    public function saveData($request, $id=false){
        $jurusan = new Jurusan;
        if($id) $jurusan = self::find($id);
        $jurusan->id_account = $request->id_account;
        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->nama_jurusan_en = $request->nama_jurusan_en;
        $jurusan->program = $request->program;
        $jurusan->jenis_pendidikan = $request->jenis_pendidikan;
        $jurusan->fakultas = $request->fakultas;
        $jurusan->gelar = $request->gelar;
        $jurusan->persyaratan = $request->persyaratan;
        $jurusan->persyaratan_en = $request->persyaratan_en;
        $jurusan->penilaian = $request->penilaian;
        if($jurusan->save()){
            return ['message' => 'Save Jurusan Successfull'];
        }else{
            return ['message' => 'Save Jurusan Failed'];
        }
    }

    public function deleteData($id){
        $jurusan = self::find($id);
        if($jurusan->delete()){
            return ['message' => 'Delete Jurusan Successfull'];
        }else{
            return ['message' => 'Delete Jurusan Failed'];
        }
    }

    public function getJurusanData(){
        $jurusan = self::select('id','nama_jurusan')->get();
        return JurusanResource::collection($jurusan);
    }

    public function user(){
        return $this->hasOne('Apkom\User', 'id', 'id_account')->select('id','name');
    }

    public function kompetensiWajib(){
        return $this->hasMany('Apkom\KompetensiWajib', 'id_jurusan');
    }

    public function mahasiswa(){
        return $this->hasMany('Apkom\Mahasiswa', 'id_jurusan');
    }
}
