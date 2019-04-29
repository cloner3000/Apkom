<?php

namespace Apkom;

use Illuminate\Database\Eloquent\Model;

class kemampuan extends Model
{
    protected $table = 'kemampuan';
    
    protected $fillable = [
        'id_kompetensi',
        'nama_kemampuan'
    ];
}
