<?php

namespace Apkom\Exports;

use Apkom\Kompetensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KompetensiExport implements FromCollection, WithMapping, WithHeadings
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Kompetensi::with('bidangKompetensi')->get();
    }

    public function map($kompetensi): array
    {
        return [
            $this->i++,
            $kompetensi->nama_kompetensi,
            $kompetensi->bidangKompetensi->nama_bidang,            
            $kompetensi->tgl_mulai.' - '.$kompetensi->tgl_selesai,
            $kompetensi->tingkat,
            $kompetensi->peran,
            $kompetensi->point_kompetensi
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA KOMPETENSI',
            'BIDANG',
            'TANGGAL KEGIATAN',
            'TINGKAT',
            'PERAN',
            'POINT'
        ];
    }
}
