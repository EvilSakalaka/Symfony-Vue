<?php

namespace App\Entity;

use App\Repository\ApiRoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ApiRoleRepository::class)]
class ApiRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: ApiUser::class, mappedBy: 'roles')]
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function getUsers(): Collection
    {
        return $this->users;
    }
}
