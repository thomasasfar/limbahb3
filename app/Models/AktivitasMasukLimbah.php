<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AktivitasLimbah;
use App\Models\KlasifikasiLimbah;
use App\Models\User;
use App\Models\Unit;


class AktivitasMasukLimbah extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function klasifikasi_limbah(){
        return $this->belongsTo(KlasifikasiLimbah::class,'id_klasifikasilimbah');
    }
    public function aktivitas_limbah(){
        return $this->belongsTo(AktivitasLimbah::class,'id_aktivitaslimbah');
    }
    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }
    public function pengajuan(){
        return $this->belongsTo(Pengajuan::class,'id_user');
    }

    // relation to unit stored in `sumber` (now stores unit_id)
    public function sumber_unit()
    {
        return $this->belongsTo(Unit::class, 'sumber');
    }
}