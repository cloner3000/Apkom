<?php

namespace Apkom;

use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\BuktiKompetensiWajibResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class BuktiKompetensiWajib extends Model
{
    protected $table ='bukti_kompetensi_wajib';
    
    protected $fillable = [
        'id_mahasiswa',
        'bukti'
    ];

    public function getData(){
        $buktiKompetensiWajib = self::where('id_mahasiswa',auth('api')->user()->mahasiswa()->id)
        ->paginate(8);
        return BuktiKompetensiWajibResource::collection($buktiKompetensiWajib);
    }

    public function saveData($request, $id){
        $buktiKompetensiWajib = self::find($id);
        if($buktiKompetensiWajib->id_mahasiswa == auth('api')->user()->mahasiswa()->id){
            $extention = explode('/', explode(':', substr($request->bukti_wajib, 0, strpos($request->bukti_wajib, ';')))[1])[1];
            $fileName = time().uniqid().'.'.$extention;
                
            if($extention == 'pdf'){
                $explode = explode(",", $request->bukti_wajib);
                $decoded_file = base64_decode($explode['1']);
                Storage::disk('kompetensi')->put($fileName, $decoded_file);
            }else{
                $img = Image::make($request->bukti_wajib);
                $img->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('storage/data/kompetensi/img/').$fileName);
            }
            if($buktiKompetensiWajib->bukti_wajib != null){
                if(substr($buktiKompetensiWajib->bukti_wajib, strpos($buktiKompetensiWajib->bukti_wajib, '.')+1) == 'pdf'){
                    $fileTemp = public_path('storage/data/kompetensi/pdf/').$buktiKompetensiWajib->bukti_wajib;
                }else{
                    $fileTemp = public_path('storage/data/kompetensi/img/').$buktiKompetensiWajib->bukti_wajib;
                }
                if(file_exists($fileTemp)){
                    @unlink($fileTemp);
                }
            }
            $buktiKompetensiWajib->bukti_wajib = $fileName;
            $buktiKompetensiWajib->save();
            return $buktiKompetensiWajib;
        }
    }

    public function searchData($search){
        $buktiKompetensiWajib = self::where('id_mahasiswa',auth('api')->user()->mahasiswa()->id)
            ->where('nama_kompetensi_wajib','LIKE',"%$search%")
            ->paginate(8);
        return BuktiKompetensiWajibResource::collection($buktiKompetensiWajib);
    }
    
}
