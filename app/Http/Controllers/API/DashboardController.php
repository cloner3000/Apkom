<?php

namespace Apkom\Http\Controllers\API;

use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Apkom\Skpi;
use Apkom\Mahasiswa;
use Apkom\BidangKompetensi;

class DashboardController extends Controller
{
    public function __construct(Skpi $skpi, Mahasiswa $mahasiswa, BidangKompetensi $bidangKompetensi){
        $this->middleware('auth:api');
        $this->skpi = $skpi;
        $this->mahasiswa = $mahasiswa;
        $this->bidangKompetensi = $bidangKompetensi;
    }

    public function index(){
        $this->authorize('isAdmin');
        if(Gate::check('isKaprodi')){
            $mahasiswa = $this->mahasiswa->countData(auth('api')->user()->id);
            $skpi = $this->skpi->countData(auth('api')->user()->id);
        }else{
            $mahasiswa = $this->mahasiswa->countData();
            $skpi = $this->skpi->countData();    
        }
        $bidangKompetensi = $this->bidangKompetensi->countData();
        return ['skpiProgress' => $skpi[0],
                'skpiPublished' => $skpi[1],
                'mahasiswa' => $mahasiswa,
                'bidangKompetensi' => $bidangKompetensi
            ];
    }
}
