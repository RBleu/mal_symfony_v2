<?php

namespace App\Entity;

use App\Repository\AnimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimeRepository::class)]
#[ORM\Table(name: 'ms_anime')]
class Anime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'a_id')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, name: 'a_title')]
    private $title;

    #[ORM\Column(type: 'integer', nullable: true, name: 'a_episodes')]
    private $episodes;

    #[ORM\Column(type: 'smallint', name: 'a_airing')]
    private $airing;

    #[ORM\Column(type: 'string', length: 255, name: 'a_status')]
    private $status;

    #[ORM\Column(type: 'date', nullable: true, name: 'a_aired_from')]
    private $airedFrom;

    #[ORM\Column(type: 'date', nullable: true, name: 'a_aired_to')]
    private $airedTo;

    #[ORM\Column(type: 'string', length: 255, nullable: true, name: 'a_aired')]
    private $aired;

    #[ORM\Column(type: 'string', length: 255, nullable: true, name: 'a_duration')]
    private $duration;

    #[ORM\Column(type: 'float', nullable: true, name: 'a_score')]
    private $score;

    #[ORM\Column(type: 'integer', nullable: true, name: 'a_scored_by')]
    private $scoredBy;

    #[ORM\Column(type: 'integer', nullable: true, name: 'a_rank')]
    private $rank;

    #[ORM\Column(type: 'text', nullable: true, name: 'a_synopsis')]
    private $synopsis;

    #[ORM\Column(type: 'string', length: 11, nullable: true, name: 'a_premiered')]
    private $premiered;

    #[ORM\Column(type: 'string', length: 255, nullable: true, name: 'a_cover')]
    private $cover;

    #[ORM\Column(type: 'integer', nullable: true, name: 'a_members')]
    private $members;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'animes')]
    #[ORM\JoinColumn(nullable: false, name: 'a_type_id', referencedColumnName: 'ty_id')]
    private $type;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'animes')]
    #[ORM\JoinTable(name: 'ms_anime_genre')]
    #[ORM\JoinColumn(name: 'ag_anime_id', referencedColumnName: 'a_id')]
    #[ORM\InverseJoinColumn(name: 'ag_genre_id', referencedColumnName: 'g_id')]
    private $genres;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'sequels')]
    #[ORM\JoinTable(name: 'ms_anime_relation')]
    #[ORM\JoinColumn(name: 'ar_sequel_id', referencedColumnName: 'a_id')]
    #[ORM\InverseJoinColumn(name: 'ar_prequel_id', referencedColumnName: 'a_id')]
    private $prequels;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'prequels')]
    private $sequels;

    #[ORM\OneToMany(mappedBy: 'anime', targetEntity: Theme::class)]
    private $themes;

    #[ORM\OneToMany(mappedBy: 'anime', targetEntity: UserList::class)]
    private $userLists;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->prequel = new ArrayCollection();
        $this->sequel = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->userLists = new ArrayCollection();
    }

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

    public function getEpisodes(): ?int
    {
        return $this->episodes;
    }

    public function setEpisodes(?int $episodes): self
    {
        $this->episodes = $episodes;

        return $this;
    }

    public function getAiring(): ?int
    {
        return $this->airing;
    }

    public function setAiring(int $airing): self
    {
        $this->airing = $airing;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAiredFrom(): ?\DateTimeInterface
    {
        return $this->airedFrom;
    }

    public function setAiredFrom(?\DateTimeInterface $airedFrom): self
    {
        $this->airedFrom = $airedFrom;

        return $this;
    }

    public function getAiredTo(): ?\DateTimeInterface
    {
        return $this->airedTo;
    }

    public function setAiredTo(?\DateTimeInterface $airedTo): self
    {
        $this->airedTo = $airedTo;

        return $this;
    }

    public function getAired(): ?string
    {
        return $this->aired;
    }

    public function setAired(?string $aired): self
    {
        $this->aired = $aired;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getScoredBy(): ?int
    {
        return $this->scoredBy;
    }

    public function setScoredBy(?int $scoredBy): self
    {
        $this->scoredBy = $scoredBy;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPremiered(): ?string
    {
        return $this->premiered;
    }

    public function setPremiered(?string $premiered): self
    {
        $this->premiered = $premiered;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getMembers(): ?int
    {
        return $this->members;
    }

    public function setMembers(?int $members): self
    {
        $this->members = $members;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPrequels(): Collection
    {
        return $this->prequels;
    }

    public function addPrequel(self $prequels): self
    {
        if (!$this->prequels->contains($prequels)) {
            $this->prequels[] = $prequels;
        }

        return $this;
    }

    public function removePrequels(self $prequels): self
    {
        $this->prequels->removeElement($prequels);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSequels(): Collection
    {
        return $this->sequels;
    }

    public function addSequels(self $sequels): self
    {
        if (!$this->sequels->contains($sequels)) {
            $this->sequels[] = $sequels;
            $sequels->addPrequel($this);
        }

        return $this;
    }

    public function removeSequels(self $sequels): self
    {
        if ($this->sequel->removeElement($sequels)) {
            $sequels->removePrequels($this);
        }

        return $this;
    }

    /**
     * @return Collection|Theme[]
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->themes->contains($theme)) {
            $this->themes[] = $theme;
            $theme->setAnime($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->themes->removeElement($theme)) {
            // set the owning side to null (unless already changed)
            if ($theme->getAnime() === $this) {
                $theme->setAnime(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserList[]
     */
    public function getUserLists(): Collection
    {
        return $this->userLists;
    }

    public function addUserList(UserList $userList): self
    {
        if (!$this->userLists->contains($userList)) {
            $this->userLists[] = $userList;
            $userList->setAnime($this);
        }

        return $this;
    }

    public function removeUserList(UserList $userList): self
    {
        if ($this->userLists->removeElement($userList)) {
            // set the owning side to null (unless already changed)
            if ($userList->getAnime() === $this) {
                $userList->setAnime(null);
            }
        }

        return $this;
    }
}
