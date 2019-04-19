<?php

namespace Apkom\Exports;

use Apkom\BidangKompetensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BidangKompetensiExport implements FromCollection, WithMapping, WithHeadings
{
    private $i =1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BidangKompetensi::all();
    }

    public function map($bidangKompetensi): array
    {
        return [
            $this->i++,
            $bidangKompetensi->nama_bidang
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA BIDANG'
        ];
    }
}
