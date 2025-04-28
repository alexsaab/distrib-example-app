<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use App\Repository\SaleRepository;
#[ORM\Entity(repositoryClass: SaleRepository::class)]
#[ORM\Table(name: 'sales')]
#[ORM\Index(fields: ['taxId'])]
#[ORM\Index(fields: ['brand'])]
#[ORM\Index(fields: ['sku'])]
#[ORM\Index(fields: ['salesDate'])]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private ?string $taxId = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $salesDate = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true, options: ['default' => 'Miles'])]
    private string $brand;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    private ?string $sku;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 1])]
    private ?int $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxId(): ?string
    {
        return $this->taxId;
    }

    public function setTaxId(?string $taxId): self
    {
        $this->taxId = $taxId;
        return $this;
    }

    public function getSalesDate(): ?\DateTime
    {
        return $this->salesDate;
    }

    public function setSalesDate(?\DateTime $salesDate): self
    {
        $this->salesDate = $salesDate;
        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function __toString(): string
    {
        return json_encode($this);
    }
}