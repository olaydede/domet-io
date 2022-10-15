<?php

namespace App\Entity;

use App\Enum\TaskType;
use App\Repository\TaskRepository;
use App\Traits\Entity\BasicEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{
    use BasicEntityTrait;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private string $taskType;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'authoredTasks')]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'assignedTasks')]
    private ?User $assignee = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: Domet::class)]
    private Collection $domets;

    public function __construct()
    {
        $this->domets = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTaskType(): TaskType
    {
        return TaskType::from($this->taskType);
    }

    public function setTaskType(TaskType $taskType): self
    {
        $this->taskType = $taskType->name;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * @return Collection<int, Domet>
     */
    public function getDomets(): Collection
    {
        return $this->domets;
    }

    public function addDomet(Domet $domet): self
    {
        if (!$this->domets->contains($domet)) {
            $this->domets->add($domet);
            $domet->setTask($this);
        }

        return $this;
    }

    public function removeDomet(Domet $domet): self
    {
        if ($this->domets->removeElement($domet)) {
            // set the owning side to null (unless already changed)
            if ($domet->getTask() === $this) {
                $domet->setTask(null);
            }
        }

        return $this;
    }

    public function getColor(): string
    {
        return $this->project->getColor() ??
            '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}
