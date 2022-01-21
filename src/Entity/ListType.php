<?php

namespace App\Entity;

use App\Repository\ListTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListTypeRepository::class)]
#[ORM\Table(name: 'ms_list_type')]
class ListType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'lt_id')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, name: 'lt_name')]
    private $name;

    #[ORM\Column(type: 'string', length: 255, name: 'lt_list_key')]
    private $listKey;

    #[ORM\OneToMany(mappedBy: 'listType', targetEntity: UserList::class)]
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

    public function getListKey(): ?string
    {
        return $this->listKey;
    }

    public function setListKey(string $listKey): self
    {
        $this->listKey = $listKey;

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
            $userList->setListType($this);
        }

        return $this;
    }

    public function removeUserList(UserList $userList): self
    {
        if ($this->userLists->removeElement($userList)) {
            // set the owning side to null (unless already changed)
            if ($userList->getListType() === $this) {
                $userList->setListType(null);
            }
        }

        return $this;
    }
}
