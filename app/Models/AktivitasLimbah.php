<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AktivitasKeluarLimbah;
use App\Models\AktivitasMasukLimbah;
use App\Models\User;


class AktivitasLimbah extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function aktivitas_keluar_limbah(){
        return $this->hasMany(AktivitasKeluarLimbah::class,'id_aktivitaslimbah');
    }
    public function aktivitas_masuk_limbah(){
        return $this->hasMany(AktivitasMasukLimbah::class,'id_aktivitaslimbah');
    }
    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }
    public function pengajuan(){
        return $this->hasMany(Pengajuan::class,'id_aktivitaslimbah');
    }
}