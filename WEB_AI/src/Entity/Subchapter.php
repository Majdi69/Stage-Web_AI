<?php

namespace App\Entity;

use App\Repository\SubchaptersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubchaptersRepository::class)]
class Subchapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'Subchapters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chapter $chapter = null;

    #[ORM\OneToMany(mappedBy: 'subchapter', targetEntity: Quizz::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $Quizzs;

    public function __construct()
    {
        $this->Quizzs = new ArrayCollection();
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

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * @return Collection<int, Quizz>
     */
    public function getQuizzs(): Collection
    {
        return $this->Quizzs;
    }

    public function addQuizz(Quizz $quizz): static
    {
        if (!$this->Quizzs->contains($quizz)) {
            $this->Quizzs->add($quizz);
            $quizz->setSubchapter($this);
        }

        return $this;
    }

    public function removeQuizz(Quizz $quizz): static
    {
        if ($this->Quizzs->removeElement($quizz)) {
            // set the owning side to null (unless already changed)
            if ($quizz->getSubchapter() === $this) {
                $quizz->setSubchapter(null);
            }
        }

        return $this;
    }
}
