<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
#[ORM\Entity]
#[ORM\Table(name: 'returns')]
#[ORM\Index(fields: ['taxId'])]
#[ORM\Index(fields: ['brand'])]
#[ORM\Index(fields: ['sku'])]   
#[ORM\Index(fields: ['salesDate'])]  
#[ORM\Index(fields: ['returnDate'])]
class ReturnData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 15, nullable: false)]
    private ?string $taxId;

    #[ORM\Column(type: 'string', length: 100, nullable: false, options: ['default' => 'Miles'])]
    private ?string $brand;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    private ?string $sku;

    #[ORM\Column(type: 'date', nullable: false)]
    private ?DateTime $salesDate;

    #[ORM\Column(type: 'date', nullable: false)]
    private ?DateTime $returnDate;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 1])]
    private ?int $quantity;

    // getters and setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxId(): ?string
    {
        return $this->taxId;
    }

    public function getBrand(): ?string 
    {
        return $this->brand;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }
    
    public function getSalesDate(): ?DateTime
    {
        return $this->salesDate;
    }

    public function getReturnDate(): ?DateTime
    {   
        return $this->returnDate;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }   

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }   

    public function setTaxId(string $taxId): self
    {
        $this->taxId = $taxId;
        return $this;
    }       

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }   

    public function setSku(string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }   

    public function setSalesDate(DateTime $salesDate): self
    {
        $this->salesDate = $salesDate;
        return $this;
    }   

    public function setReturnDate(DateTime $returnDate): self
    {
        $this->returnDate = $returnDate;
        return $this;
    }   

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }                   

    public function __toString(): string
    {
        return json_encode($this);
    }
}