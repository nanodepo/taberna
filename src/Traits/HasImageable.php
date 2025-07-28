<?php

namespace NanoDepo\Taberna\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use NanoDepo\Taberna\Models\Image;

trait HasImageable
{
    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'subject_id')
            ->orderByDesc('is_primary');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'subject');
    }
}

