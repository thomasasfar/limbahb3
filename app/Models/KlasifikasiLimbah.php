<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AktivitasKeluarLimbah;
use App\Models\AktivitasMasukLimbah;

class KlasifikasiLimbah extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function aktivitas_keluar_limbah(){
        return $this->hasMany(AktivitasKeluarLimbah::class,'id_klasifikasilimbah');
    }
    public function aktivitas_masuk_limbah(){
        return $this->hasMany(AktivitasMasukLimbah::class,'id_klasifikasilimbah');
    }
}
