<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'ms_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'u_id')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true, name: 'u_username')]
    #[Assert\Length(
        min: 4, 
        max: 20, 
        minMessage: 'Your username must be at least {{ limit }} characters long',
        maxMessage: 'Your username cannot be longer than {{ limit }} characters'
    )]
    private $username;

    #[ORM\Column(type: 'json', name: 'u_roles')]
    private $roles = [];

    #[ORM\Column(type: 'string', name: 'u_password')]
    private $password;

    #[ORM\Column(type: 'string', length: 255, name: 'u_email')]
    private $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true, name: 'u_image')]
    private $image;

    #[ORM\Column(type: 'date', name: 'u_signup_date')]
    private $signupDate;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserList::class)]
    private $userLists;

    #[Assert\Length(
        min: 8, 
        max: 50, 
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters'
    )]
    private $plainPassword;

    #[Assert\Length(
        min: 8, 
        max: 50, 
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters'
    )]
    private $plainPasswordConfirm;

    public function __construct()
    {
        $this->userLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
        $this->plainPasswordConfirm = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSignupDate(): ?\DateTimeInterface
    {
        return $this->signupDate;
    }

    public function setSignupDate(\DateTimeInterface $signupDate): self
    {
        $this->signupDate = $signupDate;

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
            $userList->setUser($this);
        }

        return $this;
    }

    public function removeUserList(UserList $userList): self
    {
        if ($this->userLists->removeElement($userList)) {
            // set the owning side to null (unless already changed)
            if ($userList->getUser() === $this) {
                $userList->setUser(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPasswordConfirm(): ?string
    {
        return $this->plainPasswordConfirm;
    }

    public function setPlainPasswordConfirm(string $plainPasswordConfirm): self
    {
        $this->plainPasswordConfirm = $plainPasswordConfirm;

        return $this;
    }
}
