<?php

namespace App\Entity;

use App\Repository\SubjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectsRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'sub')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $levels = null;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: Chapter::class, orphanRemoval: true)]
    private Collection $Chapters;

    public function __construct()
    {
        $this->Chapters = new ArrayCollection();
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

    public function getLevels(): ?Level
    {
        return $this->levels;
    }

    public function setLevels(?Level $levels): static
    {
        $this->levels = $levels;

        return $this;
    }

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->Chapters;
    }

    public function addChapter(Chapter $chapter): static
    {
        if (!$this->Chapters->contains($chapter)) {
            $this->Chapters->add($chapter);
            $chapter->setSubject($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): static
    {
        if ($this->Chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getSubject() === $this) {
                $chapter->setSubject(null);
            }
        }

        return $this;
    }
}
