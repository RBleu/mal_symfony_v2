<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
#[ORM\Table(name: 'ms_theme')]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'th_id')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, name: 'th_name')]
    private $name;

    #[ORM\Column(type: 'string', length: 255, name: 'th_type')]
    private $type;

    #[ORM\ManyToOne(targetEntity: Anime::class, inversedBy: 'themes')]
    #[ORM\JoinColumn(nullable: false, name: 'th_anime_id', referencedColumnName: 'a_id')]
    private $anime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAnime(): ?Anime
    {
        return $this->anime;
    }

    public function setAnime(?Anime $anime): self
    {
        $this->anime = $anime;

        return $this;
    }
}
