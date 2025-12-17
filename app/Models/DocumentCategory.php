<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'image_path',
        'manager',
        'order',
        'published_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get active categories by type, optionally filtered by year and month
     */
    public static function getByType($type, $year = null, $month = null, $day = null, $limit = null)
    {
        $query = self::where('type', $type)
            ->where('is_active', true);

        if ($year) {
            $query->whereYear('published_at', $year);
        }

        if ($month) {
            $query->whereMonth('published_at', $month);
        }

        if ($day) {
            $query->whereDay('published_at', $day);
        }

        $query->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : 'https://placehold.co/300x180?text=' . urlencode($this->title);
    }

    public function getTypeLabel()
    {
        $labels = [
            'fund_fact_sheet' => 'Fund Fact Sheet',
            'laporan_mingguan' => 'Laporan Mingguan',
            'laporan_bulanan' => 'Laporan Bulanan',
            'laporan_tahunan' => 'Laporan Tahunan',
        ];

        return $labels[$this->type] ?? $this->type;
    }
}