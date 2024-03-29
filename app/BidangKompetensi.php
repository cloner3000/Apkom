<?php

namespace Apkom;

use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\BidangKompetensiResource;

class BidangKompetensi extends Model
{
    protected $table = 'bidang_kompetensi';

    protected $fillable = [
        'nama_bidang',
        'nama_bidang_en'
    ];

    public function getData(){
        $bidangKompetensi = self::paginate(8);
        return BidangKompetensiResource::collection($bidangKompetensi);
    }

    public function getDataReport(){
        $bidangKompetensi = self::all();
        return BidangKompetensiResource::collection($bidangKompetensi);
    }

    public function searchData($search){
        $bidangKompetensi = self::where('nama_bidang','LIKE',"%$search%")
        ->paginate(8);
        return BidangKompetensiResource::collection($bidangKompetensi);
    }

    public function saveData($request, $id=false){
        $bidangKompetensi = new BidangKompetensi;
        if($id) $bidangKompetensi = self::find($id);
        $bidangKompetensi->nama_bidang = $request->nama_bidang;
        $bidangKompetensi->nama_bidang_en = $request->nama_bidang_en;
        if($bidangKompetensi->save()){
            return ['message' => 'Save Bidang Kompetensi Successfull'];
        }else{
            return ['message' => 'Save Bidang Kompetensi Failed'];
        }
    }

    public function deleteData($id){
        $bidangKompetensi = self::find($id);
        if($bidangKompetensi->delete()){
            return ['message' => 'Delete Bidang Kompetensi Successfull'];
        }else{
            return ['message' => 'Delete Bidang Kompetensi Failed'];
        }
    }

    public function getBidangKompetensiData(){
        $bidangKompetensi = self::select('id','nama_bidang')->get();
        return BidangKompetensiResource::collection($bidangKompetensi);
    }

    public function countData(){
        $bidangKompetensi = self::count();
        return $bidangKompetensi;
    }

    public function kompetensi(){
        return $this->hasMany('Apkom\Kompetensi', 'id_bidang');
    }
}
