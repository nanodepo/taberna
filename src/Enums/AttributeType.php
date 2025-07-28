<?php

namespace NanoDepo\Taberna\Enums;

enum AttributeType: string
{
    case Input = 'input';
    case Select = 'select';
    case Checkbox = 'checkbox';
    case Color = 'color';

    public function title(): string
    {
        return __('enum.attributes.'.$this->value.'.title');
    }

    public function description(): string
    {
        return __('enum.attributes.'.$this->value.'.description');
    }
}
