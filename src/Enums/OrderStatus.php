<?php

namespace NanoDepo\Taberna\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Sent = 'sent';
    case Completed = 'completed';
    case Canceled = 'canceled';
    case Failed = 'failed';

    public function title(): string
    {
        return __('enum.order.'.$this->value.'.title');
    }
}
