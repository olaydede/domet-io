<?php

namespace App\Entity;

use App\Repository\DometPartRepository;
use App\Traits\Entity\BasicEntityTrait;
use App\Traits\Entity\SoftDeletableEntityTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DometPartRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DometPart
{
    use BasicEntityTrait;
    use SoftDeletableEntityTrait;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $beginDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'dometParts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Domet $Domet = null;

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDomet(): ?Domet
    {
        return $this->Domet;
    }

    public function setDomet(?Domet $Domet): self
    {
        $this->Domet = $Domet;

        return $this;
    }
}
