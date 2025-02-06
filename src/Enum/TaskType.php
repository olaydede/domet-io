<?php

namespace App\Enum;

use App\Contracts\LabeledType;

enum TaskType: string implements LabeledType
{
    case REGULAR = 'REGULAR';
    case SPECIAL = 'SPECIAL';

    public function label()
    {
        return match ($this) {
            self::SPECIAL => 'Special task',
            default => 'Regular task'
        };
    }
}
