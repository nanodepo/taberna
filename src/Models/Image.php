<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'subject_id',
        'subject_type',
        'disk',
        'path',
        'is_primary',
        'alt',
    ];

    public function thumbnail(int $size = 1000): string
    {
        return thumbnail(item: $this->path, size: $size.'x'.$size, dir: $this->disk);
    }
}
