<?php

namespace Apkom;

use Apkom\BuktiKompetensiWajib;
use Apkom\KompetensiWajib;
use Apkom\Jurusan;
use Illuminate\Database\Eloquent\Model;
use Apkom\Http\Resources\MahasiswaResource;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'id_jurusan',
        'id_account',
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
        $mahasiswa = self::with('jurusan')->with('user')->latest()->paginate(8);
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
            if($mahasiswa->id_jurusan != $request->id_jurusan){
                $mahasiswa->buktiKompetensiWajib()->delete();
            }else{
                $storeKompetensiWajib = false;
            }
        }
        $mahasiswa->id_jurusan = $request->id_jurusan;
        $mahasiswa->id_account = $request->id_account;
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

    public function setPoint($id){
        $mahasiswa = self::find($id);
        $wIpk = 4 / (4+2+1);
        $wSkpi = 2 / (4+2+1);
        $wStudi = -1*(1 / (4+2+1));
        $ipk = $mahasiswa['ipk'];
        $period =  date_diff(date_create($mahasiswa->tgl_masuk), date_create($mahasiswa->tgl_lulus));
        $studi = ($period->format('%y')* 12) + $period->format('%m');
        $skpi = $mahasiswa->skpi->point_skpi;
        $s = pow($ipk, $wIpk) * pow($studi, $wStudi) * pow($skpi, $wSkpi);
        $mahasiswa->total_point = round($s,5);
        if($mahasiswa->save()){
            return ['message' => 'Mahasiswa Total Point has calculate',
            'IPK' => $ipk.' pangkat  '.$wIpk.' = '.pow($ipk, $wIpk),
            'SKPI' => $skpi.' pangkat  '.$wSkpi.' = '.pow($skpi, $wSkpi),
            'Studi' => $studi.' pangkat  '.$wStudi.' = '.pow($studi, $wStudi),
        's' => $s];
        }else{
            return ['message' => 'Failed calculate Total Point'];
        }
    }

    public function mahasiswaAchievement(){
        $total = self::sum('total_point');
        $mahasiswa= [];
        $dataMahasiswa = self::with('jurusan')->where('total_point', '!=', 0.00)->get();
        foreach($dataMahasiswa as $data){
            array_push($mahasiswa,
            [
                'nama' => $data->nama,
                'npm' => $data->npm,
                'jurusan' => $data->jurusan->nama_jurusan,
                'total_point' => round($data->total_point / $total,5)
            ]);
        }
        usort($mahasiswa, function($a, $b){
            return -1 * strcmp($a['total_point'], $b['total_point']);
        });
        return $mahasiswa;
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

    public function countData($id=false){
        if($id){
            $jurusan = Jurusan::where('id_account', $id)->select('id')->first();
            $mahasiswa = self::where('id_jurusan', $jurusan->id)->count();
        }else{
            $mahasiswa = self::count();
        }
        return $mahasiswa;
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

    public function kemampuan(){
        return $this->hasManyThrough('Apkom\Kemampuan', 'Apkom\Kompetensi', 'id_mahasiswa', 'id_kompetensi', 'id', 'id');
    }

}
