<?php

namespace App\Entity;

use App\Repository\UserListRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserListRepository::class)]
#[ORM\Table(name: 'ms_user_list')]
class UserList
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userLists')]
    #[ORM\JoinColumn(nullable: false, name: 'ul_user_id', referencedColumnName: 'u_id')]
    private $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Anime::class, inversedBy: 'userLists')]
    #[ORM\JoinColumn(nullable: false, name: 'ul_anime_id', referencedColumnName: 'a_id')]
    private $anime;

    #[ORM\ManyToOne(targetEntity: ListType::class, inversedBy: 'userLists')]
    #[ORM\JoinColumn(nullable: false, name: 'ul_list_type_id', referencedColumnName: 'lt_id')]
    private $listType;

    #[ORM\Column(type: 'integer', name: 'ul_score')]
    private $score;

    #[ORM\Column(type: 'text', nullable: true, name: 'ul_comment')]
    private $comment;

    #[ORM\Column(type: 'datetime', name: 'ul_modification_date')]
    private $modificationDate;

    #[ORM\ManyToOne(targetEntity: Priority::class, inversedBy: 'userLists')]
    #[ORM\JoinColumn(nullable: false, name: 'ul_priority_id', referencedColumnName: 'p_id')]
    private $priority;

    #[ORM\Column(type: 'integer', name: 'ul_progress_episodes')]
    private $progressEpisodes;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getListType(): ?ListType
    {
        return $this->listType;
    }

    public function setListType(?ListType $listType): self
    {
        $this->listType = $listType;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getProgressEpisodes(): ?int
    {
        return $this->progressEpisodes;
    }

    public function setProgressEpisodes(int $progressEpisodes): self
    {
        $this->progressEpisodes = $progressEpisodes;

        return $this;
    }
}
