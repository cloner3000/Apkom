<?php

namespace Apkom\Imports;

use Apkom\Mahasiswa;
use Apkom\User;
use Apkom\Jurusan;
use Apkom\KompetensiWajib;
use Apkom\BuktiKompetensiWajib;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;

class MahasiswaImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row){
            $user = User::create([
                'name' => $row['nama'],
                'email' => $row['email'],
                'password' => Hash::make($row['npm'])
            ]);
            $id_jurusan = Jurusan::where('nama_jurusan', $row['id_jurusan'])->first()->id;
            $mahasiswa = Mahasiswa::create([
                'id_jurusan' => $id_jurusan,
                'id_account' => $user->id,
                'npm' => $row['npm'],
                'nama' => $row['nama'],
                'kota_lahir' => $row['kota_lahir'],
                'tgl_lahir' =>  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir']),
                'no_ijazah' => $row['no_ijazah'],
                'tgl_masuk' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_masuk']),
                'tgl_lulus' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lulus']),
                'ipk' => $row['ipk']
            ]); 
            
            $kompetensiWajib = KompetensiWajib::where('id_jurusan', $mahasiswa->id_jurusan)->select('nama_kompetensi_wajib')->get();
            if($kompetensiWajib != ''){
                foreach($kompetensiWajib as $kompetensi){
                    $buktiKompetensi = new BuktiKompetensiWajib;
                    $buktiKompetensi->id_mahasiswa = $mahasiswa->id;
                    $buktiKompetensi->nama_kompetensi_wajib = $kompetensi['nama_kompetensi_wajib'];
                    $buktiKompetensi->save();
                }
            }
        }
    }
}
