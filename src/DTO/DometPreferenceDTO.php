<?php
namespace App\DTO;

use App\Enum\DometType;

class DometPreferenceDTO
{
    public int $durationFocus;
    public int $durationBreak;

    /**
     * @param int $durationFocus
     * @param int $durationBreak
     */
    public function __construct(int $durationFocus, int $durationBreak)
    {
        $this->durationFocus = $durationFocus;
        $this->durationBreak = $durationBreak;
    }

    public function getDurationPreference(? DometType $dometType = null): int
    {
        $preferences = [
            DometType::FOCUS->value => $this->durationFocus,
            DometType::BREAK->value => $this->durationBreak
        ];
        return $preferences[$dometType ? $dometType->value : DometType::FOCUS->value];
    }
}