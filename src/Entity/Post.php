<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

// Import de la logique des slugs
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks] # Indique à doctrine pour gérer la date update automatiquement (un genre d'écouteur d'évènement)
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    // #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['title'])] // Ajouté pour le "slug"
    private $slug;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

   /*  public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    } */

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist] # Vérifie si existe ou pas
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate] # Déclanche uniquement quand il a besoin
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
