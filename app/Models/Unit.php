<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_unit',
        'kode'
    ];

    public function user(){
        return $this->hasMany(User::class, 'unit_id');
    }

}
