<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerkiraanMelahirkan extends Model
{
    use HasFactory;
    use TraitUuid;
    protected $table = 'perkiraan_melahirkan';
    protected $fillable = [
        'anggota_keluarga_id',
        'bidan_id',
        'tanggal_haid_terakhir',
        'tanggal_perkiraan_lahir',
        'is_valid',
        'tanggal_validasi',
        'alasan_ditolak',
    ];

    public function anggotaKeluarga()
    {
        return $this->belongsTo(AnggotaKeluarga::class)->withTrashed();
    }

    public function bidan()
    {
        return $this->belongsTo(Bidan::class);
    }

    public function sesuaiLokasiTugas($lokasiTugas)
    {
        return $this->hasMany(AnggotaKeluarga::class)->whereIn('desa_kelurahan_id', $lokasiTugas);
    }
}
