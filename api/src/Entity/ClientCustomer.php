<?php

namespace App\Entity;

use App\Repository\ClientCustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ClientCustomerRepository::class)
 *   @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "clientCustomer_show",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      attributes = { "method" = "GET" },
 * )
 *  @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "clientCustomer_delete",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      attributes = { "method" = "DELETE" },
 * )
 *  @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "clientCustomer_index",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      attributes = { "method" = "GET" },
 * )
 */
class ClientCustomer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
