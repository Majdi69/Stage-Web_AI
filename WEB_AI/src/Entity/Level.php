<?php

namespace App\Entity;

use App\Repository\LevelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LevelsRepository::class)]
class Level
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'levels', targetEntity: Subject::class, orphanRemoval: true)]
    private Collection $sub;

    public function __construct()
    {
        $this->sub = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSub(): Collection
    {
        return $this->sub;
    }

    public function addSub(Subject $sub): static
    {
        if (!$this->sub->contains($sub)) {
            $this->sub->add($sub);
            $sub->setLevels($this);
        }

        return $this;
    }

    public function removeSub(Subject $sub): static
    {
        if ($this->sub->removeElement($sub)) {
            // set the owning side to null (unless already changed)
            if ($sub->getLevels() === $this) {
                $sub->setLevels(null);
            }
        }

        return $this;
    }
}
