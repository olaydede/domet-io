<?php

namespace App\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
trait SoftDeletableEntityTrait
{
    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\Column]
    private ?bool $deleted = false;

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
