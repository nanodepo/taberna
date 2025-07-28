<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Feature extends Pivot
{
    public $incrementing = true;

    protected $table = 'attribute_category';

    protected $fillable = [
        'is_required',
        'default_value',
        'option_id',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
