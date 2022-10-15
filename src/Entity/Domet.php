<?php

namespace App\Entity;

use App\Enum\DometStatus;
use App\Enum\DometType;
use App\Repository\DometRepository;
use App\Traits\Entity\BasicEntityTrait;
use App\Traits\Entity\SoftDeletableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DometRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Domet
{
    use BasicEntityTrait;
    use SoftDeletableEntityTrait;

    const DEFAULT_DURATION_MS = 30 * 60 * 1000;

    #[ORM\ManyToOne(inversedBy: 'domets')]
    private ?Task $task = null;

    #[ORM\ManyToOne(inversedBy: 'domets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    private string $dometType;

    #[ORM\Column]
    private int $duration = self::DEFAULT_DURATION_MS;

    #[ORM\Column]
    private int $durationLeft = self::DEFAULT_DURATION_MS;

    #[ORM\OneToMany(mappedBy: 'Domet', targetEntity: DometPart::class, orphanRemoval: true)]
    private Collection $dometParts;

    #[ORM\Column(length: 30)]
    private ?string $status;

    public function __construct()
    {
        $this->dometParts = new ArrayCollection();
        $this->status = DometStatus::IN_PROGRESS->value;
    }

    /**
     * @return Task|null
     */
    public function getTask(): ?Task
    {
        return $this->task;
    }

    /**
     * @param Task|null $task
     * @return $this
     */
    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return DometType
     */
    public function getDometType(): DometType
    {
        return DometType::from($this->dometType);
    }

    /**
     * @param DometType $dometType
     * @return $this
     */
    public function setDometType(DometType $dometType): self
    {
        $this->dometType = $dometType->name;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getDurationLeft(): int
    {
        return $this->durationLeft;
    }

    /**
     * @param int $durationLeft
     * @return void
     */
    public function setDurationLeft(int $durationLeft): void
    {
        $this->durationLeft = $durationLeft;
    }

    /**
     * @return Collection<int, DometPart>
     */
    public function getDometParts(): Collection
    {
        return $this->dometParts;
    }

    public function addDometPart(DometPart $dometPart): self
    {
        if (!$this->dometParts->contains($dometPart)) {
            $this->dometParts->add($dometPart);
            $dometPart->setDomet($this);
        }

        return $this;
    }

    public function removeDometPart(DometPart $dometPart): self
    {
        if ($this->dometParts->removeElement($dometPart)) {
            // set the owning side to null (unless already changed)
            if ($dometPart->getDomet() === $this) {
                $dometPart->setDomet(null);
            }
        }

        return $this;
    }

    public function getStatus(): DometStatus
    {
        return DometStatus::from($this->status);
    }

    public function setStatus(DometStatus $status): self
    {
        $this->status = $status->value;

        return $this;
    }
}
