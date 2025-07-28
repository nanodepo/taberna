<?php

namespace NanoDepo\Taberna\Models;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NanoDepo\Nexus\Casts\PriceCast;
use NanoDepo\Taberna\Enums\OrderStatus;

class Order extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'price',
        'status',
        'shipping_address',
        'payment_method',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'price' => PriceCast::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
