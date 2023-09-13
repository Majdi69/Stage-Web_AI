<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'Quizzs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chapter $chapter = null;

    #[ORM\ManyToOne(inversedBy: 'Quizzs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subchapter $subchapter = null;

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

    public function getSubchapter(): ?Subchapter
    {
        return $this->subchapter;
    }

    public function setSubchapter(?Subchapter $subchapter): static
    {
        $this->subchapter = $subchapter;

        return $this;
    }
}
