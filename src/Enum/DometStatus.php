<?php

namespace App\Enum;

enum DometStatus: string
{
    case IN_PROGRESS = 'DOING';
    case COMPLETE = 'DONE';

    public function label()
    {
        return match ($this) {
            self::COMPLETE => 'Completed',
            default => 'Still working'
        };
    }
}
