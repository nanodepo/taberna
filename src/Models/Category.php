<?php

namespace NanoDepo\Taberna\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Taberna\Traits\HasImageable;
use NanoDepo\Taberna\Traits\HasMeta;

class Category extends Model
{
    use HasImageable;
    use HasMeta;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'title',
        'description',
        'icon',
        'is_active',
        'is_virtual',
    ];

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, Feature::class)
            ->withPivot(['is_required', 'default_value', 'option_id'])
            ->as('feature');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function virtual(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
