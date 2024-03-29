<?php

namespace Apkom;

use Apkom\Kemampuan;
use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\KompetensiResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Kompetensi extends Model
{
    protected $table = 'kompetensi';

    protected $fillable = [
        'id_mahasiswa',
        'id_bidang',
        'nama_kompetensi',
        'tgl_mulai',
        'tgl_selesai',
        'tingkat',
        'peran',
        'point_kompetensi',
        'bukti'
    ];

    public function getData($id = false){
        if($id){
            $id_mahasiswa = $id;
        }else{
            $id_mahasiswa = auth('api')->user()->mahasiswa()->id;
        }
        $kompetensi = self::where('id_mahasiswa', $id_mahasiswa)
        ->with('bidangKompetensi')->with('kemampuan')->latest()->paginate(8);
        return KompetensiResource::collection($kompetensi);
    }

    public function getDataReport(){
        $kompetensi = self::where('id_mahasiswa', auth('api')->user()->mahasiswa()->id)
        ->with('bidangKompetensi')->get();
        return KompetensiResource::collection($kompetensi);
    }

    public function getDataReject(){
        $kompetensi = self::where('id_mahasiswa', auth('api')->user()
        ->mahasiswa()->id)->where('active', 0)
        ->paginate(8);
        return KompetensiResource::collection($kompetensi);
    }

    public function searchData($search, $id = false){
        if($id){
            $id_mahasiswa = $id;
        }else{
            $id_mahasiswa = auth('api')->user()->mahasiswa()->id;
        }
        $kompetensi = self::where('id_mahasiswa',$id_mahasiswa)
        ->with('bidangKompetensi')
        ->with('kemampuan')
        ->join('bidang_kompetensi', 'kompetensi.id_bidang', '=', 'bidang_kompetensi.id')
        ->where('nama_kompetensi','LIKE',"%$search%")
        ->orWhere('nama_bidang','LIKE',"%$search%")
        ->select('kompetensi.*','bidang_kompetensi.nama_bidang')
        ->paginate(8);
        return KompetensiResource::collection($kompetensi);
    }

    public function saveData($request, $id=false){
        $kompetensi = new Kompetensi;
        if($id){
            $kompetensi = self::find($id);
            if($request->bukti != '') $kompetensi->bukti = $this->saveBukti($request->bukti,$id);
        }else{
            $kompetensi->bukti = $this->saveBukti($request->bukti);
        } 
        $kompetensi->id_mahasiswa = auth('api')->user()->mahasiswa()->id;
        $kompetensi->id_bidang = $request->id_bidang;
        $kompetensi->nama_kompetensi = $request->nama_kompetensi;
        $kompetensi->tgl_mulai = $request->tgl_mulai;
        $kompetensi->tgl_selesai = $request->tgl_selesai;
        $kompetensi->tingkat = $request->tingkat;
        $kompetensi->peran = $request->peran;
        $kompetensi->point_kompetensi = $this->getPointKompetensi($request->tingkat, $request->peran, $request->id_bidang);
        if($kompetensi->save()){
            if($request->id_bidang != 1){
                $kemampuan_ids = array();    
                foreach($request->kemampuan as $dataKemampuan){
                    $kemampuan = new Kemampuan;
                    $kemampuan->id_kompetensi = $kompetensi->id;
                    $kemampuan->nama_kemampuan = $dataKemampuan['nama_kemampuan'];
                    $kemampuan->save();
                    $kemampuan_ids[] = $kemampuan->id;
                }
                $sync = Kemampuan::where('id_kompetensi', $kompetensi->id)->whereNotIn('id', $kemampuan_ids)->delete();
                if($sync){
                    return ['message' => 'Update Kompetensi Successfull'];
                }else{
                    return ['message' => 'Save Kompetensi Successfull'];
                }
            }else{
                return ['message' => 'Save Kompetensi Successfull'];
            }
        }else{
            return ['message' => 'Save Kompetensi Failed'];
        }
    }

    public function deleteData($id){
        $kompetensi = self::find($id);
        if($kompetensi->id_mahasiswa == auth('api')->user()->mahasiswa()->id){
            if(substr($kompetensi->bukti, strpos($kompetensi->bukti, '.')+1) == 'pdf'){
                $fileTemp = public_path('storage/data/kompetensi/pdf/').$kompetensi->bukti;
            }else{
                $fileTemp = public_path('storage/data/kompetensi/img/').$kompetensi->bukti;
            }
            if($kompetensi->delete()){
                if(file_exists($fileTemp)){
                    @unlink($fileTemp);
                }
                return ['message' => 'Delete Kompetensi Successfull'];
            }else{
                return ['message' => 'Delete Kompetensi Failed'];
            }
        }else{
            return ['message' => 'Delete Kompetensi Failed'];
        }
    }

    public function changeValidation($id){
        $kompetensi = self::find($id);
        if($kompetensi->active == 1){
            $kompetensi->active = 0;
        }else{
            $kompetensi->active = 1;
            $kompetensi->pesan = '';
        }
        if($kompetensi->save()){
            return $kompetensi->active;
        }else{
            return ['message' => 'Change Validation Failed'];
        }
    }

    public function getPointKompetensi($tingkat,$peran, $id_bidang){
        switch($tingkat){
            case 'Internasional':
                $nilaiTingkat = 50;
                break;
            case 'Nasional':
                $nilaiTingkat = 40;
                break;
            case 'Provinsi':
                $nilaiTingkat = 30;
                break;
            case 'Kota':
                $nilaiTingkat = 15;
                break;
            case 'Universitas':
                $nilaiTingkat = 4;
                break;
            default:
                $nilaiTingkat = 0;
        }
        if($id_bidang != 1){
            switch($peran){
                case 'Ketua':
                    $nilaiPeran = 10;
                    break;
                case 'Ketua Divisi':
                    $nilaiPeran = 7;
                    break;
                case 'Anggota Panitia':
                    $nilaiPeran = 6;
                    break;
                case 'Peserta':
                    $nilaiPeran = 5;
                    break;
                case 'Juara 1':
                    $nilaiPeran = 40;
                    break;
                case 'Juara 2':
                    $nilaiPeran = 30;
                    break;   
                case 'Juara 3':
                    $nilaiPeran = 20;
                    break;
                case 'Juara Harapan':
                    $nilaiPeran = 10;
                    break;         
                default:
                    $nilaiPeran = 0;
            }
        }else{
            switch($peran){
                case 'Ketua':
                    $nilaiPeran = 50;
                    break;
                case 'Ketua Divisi':
                    $nilaiPeran = 35;
                    break;
                case 'Anggota Panitia':
                    $nilaiPeran = 30;
                    break;
                case 'Peserta':
                    $nilaiPeran = 25;
                    break;
                case 'Juara 1':
                    $nilaiPeran = 40;
                    break;
                case 'Juara 2':
                    $nilaiPeran = 30;
                    break;   
                case 'Juara 3':
                    $nilaiPeran = 20;
                    break;
                case 'Juara Harapan':
                    $nilaiPeran = 10;
                    break;         
                default:
                    $nilaiPeran = 0;
            }
        }    
        return $nilaiTingkat+$nilaiPeran;    
    }

    public function saveBukti($bukti,$id=false){
        $extention = explode('/', explode(':', substr($bukti, 0, strpos($bukti, ';')))[1])[1];
        $fileName = time().uniqid().'.'.$extention;
        if($id){
            $kompetensiTemp = self::find($id);
            if($extention == 'pdf'){
                $explode = explode(",", $bukti);
                $decoded_file = base64_decode($explode['1']);
                Storage::disk('kompetensi')->put($fileName, $decoded_file);
            }else{
                $img = Image::make($bukti);
                $img->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('storage/data/kompetensi/img/').$fileName);
            }
            if(substr($kompetensiTemp->bukti, strpos($kompetensiTemp->bukti, '.')+1) == 'pdf'){
                $fileTemp = public_path('storage/data/kompetensi/pdf/').$kompetensiTemp->bukti;
            }else{
                $fileTemp = public_path('storage/data/kompetensi/img/').$kompetensiTemp->bukti;
            }
            if(file_exists($fileTemp)){
                @unlink($fileTemp);
            }
            return $fileName;   
        }else{
            if($extention == 'pdf'){
                $explode = explode(",", $bukti);
                $decoded_file = base64_decode($explode['1']);
                Storage::disk('kompetensi')->put($fileName, $decoded_file);
            }else{
                $img = Image::make($bukti);
                $img->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('storage/data/kompetensi/img/').$fileName);
                
            }
            return $fileName;   
        }
    }

    public function savePesan($request){
        $kompetensi = self::find($request->id);
        $kompetensi->pesan = $request->pesan;
        if($kompetensi->save()){
            return ['message' => 'Save pesan Successfull'];
        }else{
            return ['message' => 'Save pesan Failed'];
        }
    }

    public function bidangKompetensi(){
        return $this->belongsTo('Apkom\BidangKompetensi','id_bidang')->select('id','nama_bidang');
    }

    public function kemampuan(){
        return $this->hasMany('Apkom\Kemampuan', 'id_kompetensi', 'id');
    }

    public function mahasiswa(){
        return $this->belongsTo('Apkom\Mahasiswa', 'id', 'id_mahasiswa');
    }

}
