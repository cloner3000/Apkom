<?php

namespace Apkom\Exports;

use Apkom\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }

    public function map($user): array
    {
        return [
            $this->i++,
            $user->name,
            $user->email,
            $user->role
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAME',
            'EMAIL',
            'ROLE',
        ];
    }
}
