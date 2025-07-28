<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NanoDepo\Taberna\Traits\HasImageable;

class Review extends Model
{
    use HasImageable;
    use HasUlids;

    protected $fillable = [
        'product_id',
        'name',
        'contacts',
        'text',
        'answer',
        'value',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean'
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
