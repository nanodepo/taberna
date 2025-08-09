<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Nexus\Casts\PriceCast;
use NanoDepo\Taberna\Traits\HasImageable;

class Variant extends Model
{
    use HasImageable;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'discount',
        'quantity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => PriceCast::class,
        ];
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class);
    }
}
