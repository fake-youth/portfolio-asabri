<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMingguan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'periode_minggu',
        'file_path',
        'tanggal_laporan',
        'uploaded_by',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
