<?php

namespace NanoDepo\Taberna\Data;

use App\Domains\User\Models\User;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public function __construct(
        public User $user,
        public Collection $items,
        public string $shipping_address,
        public string $payment_method,
        public string $comment,
    ) {}
}
