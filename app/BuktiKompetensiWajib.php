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

    public function getData($id = false){
        if($id){
            $buktiKompetensiWajib = self::where('id_mahasiswa', $id)
            ->where('bukti_wajib', '!=', null )->latest()->paginate(8);
            return BuktiKompetensiWajibResource::collection($buktiKompetensiWajib);
        }else{
            $buktiKompetensiWajib = self::where('id_mahasiswa', auth('api')->user()->mahasiswa()->id)
            ->paginate(8);
            return BuktiKompetensiWajibResource::collection($buktiKompetensiWajib);
        }
    }

    public function getDataReject(){
        $buktiKompetensiWajib = self::where('id_mahasiswa', auth('api')->user()
        ->mahasiswa()->id)->where('active', 0)
        ->paginate(8);
        return BuktiKompetensiWajibResource::collection($buktiKompetensiWajib);
    }

    public function searchData($search, $id = false){
        if($id){
            $id_mahasiswa = $id;
        }else{
            $id_mahasiswa = auth('api')->user()->mahasiswa()->id;
        }
        $buktiKompetensiWajib = self::where('id_mahasiswa', $id_mahasiswa)
            ->where('nama_kompetensi_wajib','LIKE',"%$search%")
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
            if($buktiKompetensiWajib->save()){
                return ['message' => 'Upload Bukti Kompetensi Wajib Successfull'];
            }else{
                return ['message' => 'Upload Bukti Kompetensi Wajib Failed'];
            }
        }
    }

    public function changeValidation($id){
        $buktiKompetensiWajib = self::find($id);
        if($buktiKompetensiWajib->active == 1){
            $buktiKompetensiWajib->active = 0;
        }else{
            $buktiKompetensiWajib->active = 1;
            $buktiKompetensiWajib->pesan = '';
        }
        if($buktiKompetensiWajib->save()){
            return $buktiKompetensiWajib->active;
        }else{
            return ['message' => 'Change Validation Failed'];
        }
    }

    public function savePesan($request){
        $buktiKompetensiWajib = self::find($request->id);
        $buktiKompetensiWajib->pesan = $request->pesan;
        if($buktiKompetensiWajib->save()){
            return ['message' => 'Save pesan Successfull'];
        }else{
            return ['message' => 'Save pesan Failed'];
        }
    }
    
}
