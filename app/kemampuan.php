<?php

namespace Apkom;

use Illuminate\Database\Eloquent\Model;

class Kemampuan extends Model
{
    protected $table = 'kemampuan';
    
    protected $fillable = [
        'id_kompetensi',
        'nama_kemampuan'
    ];

    public function kompetensi(){
        return $this->belongsTo('Apkom\Kompetensi','id_kompetensi');
    }
}
