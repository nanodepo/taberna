<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Nexus\Casts\PriceCast;
use NanoDepo\Taberna\Traits\HasImageable;
use NanoDepo\Taberna\Traits\HasMeta;

class Product extends Model
{
    use HasImageable;
    use HasMeta;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'name',
        'prefix',
        'intro',
        'description',
        'price',
        'discount',
        'quantity',
        'is_active',
        'has_variants',
        'is_main',
    ];

    protected function casts(): array
    {
        return [
            'price' => PriceCast::class,
        ];
    }

    public function getLinkAttribute(): string
    {
        if ($this->has_variants && $this->variants->isNotEmpty()) {
            $ids = $this->attributes()->where('is_variant_defining', true)->get()->pluck('pivot.option_id')->toArray();
            $variant = Variant::query()
                ->whereHas('options', function ($q) use ($ids) {
                    $q->whereIn('options.id', $ids);
                }, '=', count($ids))
                ->first();
            if (is_null($variant)) {
                $variant = $this->variants->first();
            }
            return route('variant', [$this->sku, $variant->sku]);
        }
        return route('product', $this->sku);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function virtual(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class)
            ->withPivot(['value', 'option_id']);
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
