<?php

namespace App\Entity;

use App\Repository\ChaptersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChaptersRepository::class)]
class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'Chapters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $subject = null;

    #[ORM\OneToMany(mappedBy: 'chapter', targetEntity: Subchapter::class, orphanRemoval: true)]
    private Collection $Subchapters;

    #[ORM\OneToMany(mappedBy: 'chapter', targetEntity: Quizz::class, orphanRemoval: true)]
    private Collection $Quizzs;

    public function __construct()
    {
        $this->Subchapters = new ArrayCollection();
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

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection<int, Subchapter>
     */
    public function getSubchapters(): Collection
    {
        return $this->Subchapters;
    }

    public function addSubchapter(Subchapter $subchapter): static
    {
        if (!$this->Subchapters->contains($subchapter)) {
            $this->Subchapters->add($subchapter);
            $subchapter->setChapter($this);
        }

        return $this;
    }

    public function removeSubchapter(Subchapter $subchapter): static
    {
        if ($this->Subchapters->removeElement($subchapter)) {
            // set the owning side to null (unless already changed)
            if ($subchapter->getChapter() === $this) {
                $subchapter->setChapter(null);
            }
        }

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
            $quizz->setChapter($this);
        }

        return $this;
    }

    public function removeQuizz(Quizz $quizz): static
    {
        if ($this->Quizzs->removeElement($quizz)) {
            // set the owning side to null (unless already changed)
            if ($quizz->getChapter() === $this) {
                $quizz->setChapter(null);
            }
        }

        return $this;
    }
}
