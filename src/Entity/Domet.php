<?php

namespace App\Entity;

use App\Enum\DometStatus;
use App\Enum\DometType;
use App\Repository\DometRepository;
use App\Traits\Entity\BasicEntityTrait;
use App\Traits\Entity\SoftDeletableEntityTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

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

    #[ORM\OneToMany(mappedBy: 'Domet', targetEntity: DometPart::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['beginDate' => 'DESC'])]
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
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface|null $user
     * @return $this
     */
    public function setUser(?UserInterface $user): self
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
     * @return $this
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
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
     * @return $this
     */
    public function setDurationLeft(int $durationLeft): self
    {
        $this->durationLeft = $durationLeft;

        return $this;
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

    public function stopWorkOn(DateTime $stopDate): self
    {
        if ($this->getDometParts()->first() instanceof DometPart &&
            is_null($this->getDometParts()->first()->getEndDate())) {
            $this->getDometParts()->first()->setEndDate($stopDate);
            $this->updateDuration();
        }
        return $this;
    }

    public function startWorkOn(DateTime $startDate): Domet
    {
        $this->stopWorkOn($startDate);
        $this->addDometPart((new DometPart())->setBeginDate($startDate));
        return $this;
    }

    private function updateDuration()
    {
        $durationLeft = $this->getDuration();
        foreach ($this->getDometParts() as $dometPart) {
            $durationLeft -= $dometPart->getDurationInMilliseconds();
        }
        if ($durationLeft <= 0) {
            $this->setStatus(DometStatus::COMPLETE);
            $this->setDurationLeft(0);
        } else {
            $this->setDurationLeft($durationLeft);
        }
    }
}
