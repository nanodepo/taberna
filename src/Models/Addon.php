<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Nexus\Casts\PriceCast;
use NanoDepo\Taberna\Traits\HasImageable;

class Addon extends Model
{
    use HasImageable;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'code',
        'max_quantity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => PriceCast::class,
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
