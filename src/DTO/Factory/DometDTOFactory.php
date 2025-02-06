<?php

namespace App\DTO\Factory;

use App\DTO\DometDTO;
use App\Entity\Domet;

class DometDTOFactory
{
    public static function makeFromDomet(Domet $domet): DometDTO
    {
        return new DometDTO(
            (string) $domet->getId(),
            (string) $domet->getUser()->getId(),
            (string) $domet->getTask()->getId(),
            (string) $domet->getDometType()->value,
            (string) $domet->getStatus()->value,
            (int) $domet->getDuration(),
            (int) $domet->getDurationLeft()
        );
    }
}
