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

    #[ORM\Column(type: 'datetime6')]
    private ?\DateTimeInterface $beginDate = null;

    #[ORM\Column(type: 'datetime6', nullable: true)]
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

    public function getDurationInMilliseconds(): int
    {
        if ($this->getBeginDate() instanceof \DateTime && $this->getEndDate() instanceof \DateTime) {
            return $this->getEndDate()->format('Uv') - $this->getBeginDate()->format('Uv');
        }
        return 0;
    }
}
