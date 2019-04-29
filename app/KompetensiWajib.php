<?php

namespace Apkom;

use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\KompetensiWajibResource;

class KompetensiWajib extends Model
{
    protected $table = 'kompetensi_wajib';

    protected $fillable = [
      'nama_kompetensi_wajib'
    ];

    protected $hidden = [
        'id_jurusan'
    ];

    public function getData(){
        $kompetensiWajib = self::where('id_jurusan', $this->getJurusan()->id)->latest()->paginate(8);
        return KompetensiWajibResource::collection($kompetensiWajib);
    }

    public function getDataReport(){
        $kompetensiWajib = self::where('id_jurusan', $this->getJurusan()->id)->get();
        $kompetensiWajib = KompetensiWajibResource::collection($kompetensiWajib);
        $jurusan = $this->getJurusan()->nama_jurusan;
        return ['kompetensiWajib' => $kompetensiWajib, 'jurusan' => $jurusan];
    }

    public function searchData($search){
        $kompetensiWajib = self::where('id_jurusan', $this->getJurusan()->id)
            ->where('nama_kompetensi_wajib','LIKE',"%$search%")
            ->paginate(8);
        return KompetensiWajibResource::collection($kompetensiWajib);
    }

    public function saveData($request, $id=false){
        $kompetensiWajib = new KompetensiWajib;
        if($id){
            $kompetensiWajib = self::find($id);
            if($kompetensiWajib->id_jurusan != $this->getJurusan()->id){
                return ['message' => 'Kompetensi Wajib Not Found'];
            }
        }
        $kompetensiWajib->id_jurusan = $this->getJurusan()->id;
        $kompetensiWajib->nama_kompetensi_wajib = $request->nama_kompetensi_wajib;
        if($kompetensiWajib->save()){
            return ['message' => 'Save Kompetensi Wajib Successfull'];
        }else{
            return ['message' => 'Save Kompetensi Wajib Failed'];
        }
    }

    public function deleteData($id){
        $kompetensiWajib = self::find($id);
        if($kompetensiWajib->id_jurusan == $this->getJurusan()->id){    
            if($kompetensiWajib->delete()){
                return ['message' => 'Delete Kompetensi Wajib Successfull'];
            }else{
                return ['message' => 'Delete Kompetensi Wajib Failed'];
            }
        }else{
            return ['message' => 'Delete Kompetensi Wajib Failed'];
        }
    }

    public function getJurusan(){
        return auth('api')->user()->jurusan();
    }

    public function jurusan(){
        return $this->belongsTo('Apkom\Jurusan', 'id_jurusan');
    }

}
