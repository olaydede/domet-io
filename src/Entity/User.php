<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\Entity\BasicEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use BasicEntityTrait;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Client::class, orphanRemoval: true)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Task::class)]
    private Collection $authoredTasks;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Task::class)]
    private Collection $authoredProjects;

    #[ORM\OneToMany(mappedBy: 'assignee', targetEntity: Task::class)]
    private Collection $assignedTasks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Domet::class, orphanRemoval: true)]
    private Collection $domets;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->authoredTasks = new ArrayCollection();
        $this->authoredProjects = new ArrayCollection();
        $this->assignedTasks = new ArrayCollection();
        $this->domets = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setUser($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getUser() === $this) {
                $client->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAuthoredTasks(): Collection
    {
        return $this->authoredTasks;
    }

    public function addAuthoredTask(Task $authoredTask): self
    {
        if (!$this->authoredTasks->contains($authoredTask)) {
            $this->authoredTasks->add($authoredTask);
            $authoredTask->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthoredTask(Task $authoredTask): self
    {
        if ($this->authoredTasks->removeElement($authoredTask)) {
            // set the owning side to null (unless already changed)
            if ($authoredTask->getAuthor() === $this) {
                $authoredTask->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAuthoredProjects(): Collection
    {
        return $this->authoredProjects;
    }

    public function addAuthoredProject(Project $authoredProject): self
    {
        if (!$this->authoredProjects->contains($authoredProject)) {
            $this->authoredProjects->add($authoredProject);
            $authoredProject->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthoredProject(Project $authoredProject): self
    {
        if ($this->authoredProjects->removeElement($authoredProject)) {
            if ($authoredProject->getAuthor() === $this) {
                $authoredProject->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAssignedTasks(): Collection
    {
        return $this->assignedTasks;
    }

    public function addAssignedTask(Task $assignedTask): self
    {
        if (!$this->assignedTasks->contains($assignedTask)) {
            $this->assignedTasks->add($assignedTask);
            $assignedTask->setAssignee($this);
        }

        return $this;
    }

    public function removeAssignedTask(Task $assignedTask): self
    {
        if ($this->assignedTasks->removeElement($assignedTask)) {
            // set the owning side to null (unless already changed)
            if ($assignedTask->getAssignee() === $this) {
                $assignedTask->setAssignee(null);
            }
        }

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
            $domet->setUser($this);
        }

        return $this;
    }

    public function removeDomet(Domet $domet): self
    {
        if ($this->domets->removeElement($domet)) {
            // set the owning side to null (unless already changed)
            if ($domet->getUser() === $this) {
                $domet->setUser(null);
            }
        }

        return $this;
    }
}
