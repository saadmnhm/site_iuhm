<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFile extends Model
{
    use SoftDeletes;

    protected $connection = 'iuhm';
    protected $table = 'media';

    protected $fillable = [
        'title', 'title_ar', 'description', 'description_ar',
        'file_name', 'file_path', 'file_size', 'file_type',
        'mime_type', 'category', 'tags', 'is_public',
        'uploaded_by', 'usage_count',
    ];

    protected $casts = [
        'tags'       => 'json',
        'is_public'  => 'boolean',
        'file_size'  => 'integer',
        'usage_count'=> 'integer',
    ];

    public $appends = ['file_size_formatted'];

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
