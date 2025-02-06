<?php

namespace App\Enum;

use App\Contracts\LabeledType;

enum DometType: string implements LabeledType
{
    case FOCUS = 'FOCUS';
    case BREAK = 'BREAK';

    public function label()
    {
        return match ($this) {
            self::BREAK => 'User is on break',
            default => 'User is focussed'
        };
    }
}
