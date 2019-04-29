<?php

namespace Apkom\Exports;

use Apkom\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaExport implements FromCollection, WithMapping, WithHeadings
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Mahasiswa::with('jurusan')->get();
    }

    public function map($mahasiswa): array
    {
        return [
            $this->i++,
            $mahasiswa->nama,
            $mahasiswa->kota_lahir.', '.$mahasiswa->tgl_lahir,
            $mahasiswa->npm,
            $mahasiswa->jurusan->nama_jurusan,
            $mahasiswa->tgl_masuk,
            $mahasiswa->tgl_lulus,
            $mahasiswa->no_ijazah,
            $mahasiswa->ipk,
            $mahasiswa->total_point,
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'TEMPAT TANGGAL LAHIR',
            'NPM',
            'JURUSAN',
            'TANGGAL MASUK',
            'TANGGAL KELULUSAN',
            'NO IJAZAH',
            'IPK',
            'TOTAL POINT',
        ];
    }
}
