<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Nexus\Casts\PriceCast;

class OrderItem extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'price_at_purchase',
        'discount_at_purchase',
        'product_name_at_purchase',
        'variant_details_at_purchase',
        'sku_at_purchase',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => PriceCast::class,
            'variant_details_at_purchase' => 'array',
        ];
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'order_item_selected_addons')
            ->withPivot([
                'addon_name_at_purchase',
                'price_at_purchase',
                'quantity',
            ]);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
}
