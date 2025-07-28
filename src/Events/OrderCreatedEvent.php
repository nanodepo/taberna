<?php

namespace NanoDepo\Taberna\Events;


use NanoDepo\Taberna\Models\Order;

class OrderCreatedEvent
{
    public function __construct(public Order $order) {}
}
