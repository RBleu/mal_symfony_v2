<?php

namespace App\Entity;

use App\Repository\PriorityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriorityRepository::class)]
#[ORM\Table(name: 'ms_priority')]
class Priority
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'p_id')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, name: 'p_name')]
    private $name;

    #[ORM\OneToMany(mappedBy: 'priority', targetEntity: UserList::class)]
    private $userLists;

    public function __construct()
    {
        $this->userLists = new ArrayCollection();
    }

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
            $userList->setPriority($this);
        }

        return $this;
    }

    public function removeUserList(UserList $userList): self
    {
        if ($this->userLists->removeElement($userList)) {
            // set the owning side to null (unless already changed)
            if ($userList->getPriority() === $this) {
                $userList->setPriority(null);
            }
        }

        return $this;
    }
}
