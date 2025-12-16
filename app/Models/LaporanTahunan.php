<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTahunan extends Model
{
    use HasFactory;



    protected $fillable = [
        'judul',
        'file_path',
        'uploaded_by',
        'tanggal_laporan',
        'tahun',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}