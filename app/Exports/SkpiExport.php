<?php

namespace Apkom\Exports;

use Apkom\Skpi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Support\Facades\Gate;

class SkpiExport implements FromCollection, WithMapping, WithHeadings, WithStrictNullComparison
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if(Gate::check('isKaprodi')){
            return Skpi::join('mahasiswa', function($join){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->where('jurusan.id_account', auth('api')->user()->id)
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.status','skpi.point_skpi','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->get();
        }else{
            return Skpi::join('mahasiswa', function($join){
                $join->on('skpi.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('jurusan', 'mahasiswa.id_jurusan', '=', 'jurusan.id')
                ->select('jurusan.nama_jurusan');
            })
            ->select('skpi.status','skpi.point_skpi','mahasiswa.nama','mahasiswa.npm','jurusan.nama_jurusan')
            ->get();
        }
    }

    public function map($skpi): array
    {
        return [
            $this->i++,
            $skpi->nama,
            $skpi->npm,
            $skpi->nama_jurusan,
            $skpi->status,
            $skpi->point_skpi,
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'NPM',
            'JURUSAN',
            'STATUS',
            'POINT SKPI'
        ];
    }
}
