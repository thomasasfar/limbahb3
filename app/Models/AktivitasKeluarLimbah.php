<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AktivitasLimbah;
use App\Models\KlasifikasiLimbah;
use App\Models\User;

class AktivitasKeluarLimbah extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function klasifikasi_limbah(){
        return $this->belongsTo(KlasifikasiLimbah::class,'id_klasifikasilimbah','id');
    }
    public function aktivitas_limbah(){
        return $this->belongsTo(AktivitasLimbah::class,'id_aktivitaslimbah','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }
}