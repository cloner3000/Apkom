<?php

namespace Apkom\Exports;

use Apkom\Jurusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JurusanExport implements FromCollection, WithMapping, WithHeadings
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Jurusan::with('user')->get();
    }

    public function map($jurusan): array
    {
        return [
            $this->i++,
            $jurusan->nama_jurusan,
            $jurusan->user->name,
            $jurusan->program,
            $jurusan->jenis_pendidikan,
            $jurusan->fakultas,
            $jurusan->gelar,
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA JURUSAN',
            'KAPRODI',
            'PROGRAM',
            'JENIS',
            'FAKULTAS',
            'GELAR',
        ];
    }
}
