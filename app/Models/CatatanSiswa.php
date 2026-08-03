<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanSiswa extends Model
{
    protected $table = 'catatan_siswa';

    protected $fillable = [
        'user_id',
        'periode',
        'bulan',
        'tahun',
        'minggu',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
