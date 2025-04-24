<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'fos_user')]
#[ApiResource]
class User extends BaseUser
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

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

    // Remove the setEmail override since we don't need to modify its behavior
    // If you want to set username same as email, you can do it when creating the user
} 