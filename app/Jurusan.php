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
      'program',
      'jenis_pendidikan',
      'fakultas',
      'gelar'
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
        $jurusan = self::with('user')->where(function($query) use ($search){
            $query->where('nama_jurusan','LIKE',"%$search%")
                    ->orWhere('fakultas','LIKE',"%$search%");
        })->paginate(8);
        return JurusanResource::collection($jurusan);
    }

    public function saveData($request, $id=false){
        $jurusan = new Jurusan;
        if($id) $jurusan = self::find($id);
        $jurusan->id_account = $request->id_account;
        $jurusan->nama_jurusan = $request->nama_jurusan;
        $jurusan->program = $request->program;
        $jurusan->jenis_pendidikan = $request->jenis_pendidikan;
        $jurusan->fakultas = $request->fakultas;
        $jurusan->gelar = $request->gelar;
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

    public function user(){
        return $this->belongsTo('Apkom\User','id_account')->select('id','name');
    }

    public function kompetensiWajib(){
        return $this->hasMany('Apkom\KompetensiWajib');
    }
}
