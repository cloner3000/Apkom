<?php

namespace Apkom\Exports;

use Apkom\KompetensiWajib;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KompetensiWajibExport implements FromCollection, WithMapping, WithHeadings
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $jurusan = auth('api')->user()->jurusan();
        return KompetensiWajib::where('id_jurusan', $jurusan->id)->get();
    }

    public function map($kompetensiWajib): array
    {
        return [
            $this->i++,
            $kompetensiWajib->nama_kompetensi_wajib
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA KOMPETENSI'
        ];
    }
}
