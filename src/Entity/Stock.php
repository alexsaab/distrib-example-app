<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'stock')]
#[ORM\Index(fields: ['brand'])]
#[ORM\Index(fields: ['sku'])]   
#[ORM\Index(fields: ['stockDate'])]  
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, nullable: false, options: ['default' => 'Miles'])]
    private ?string $brand;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    private ?string $sku;

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTime $stockDate;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 1])]
    private ?int $quantity;

    /**
     * Get the ID of the stock
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the brand of the stock
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }   

    /**
     * Get the SKU of the stock
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * Get the stock date
     * @return \DateTime|null
     */
    public function getStockDate(): ?\DateTime
    {
        return $this->stockDate;
    }

    /**
     * Get the quantity of the stock
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * Set the brand of the stock
     * @param string $brand
     * @return self
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * Set the SKU of the stock
     * @param string $sku
     * @return self
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }
    
    /**
     * Set the stock date
     * @param \DateTime $stockDate
     * @return self
     */
    public function setStockDate(\DateTime $stockDate): self
    {
        $this->stockDate = $stockDate;
        return $this;
    }

    /**
     * Set the quantity of the stock
     * @param int $quantity
     * @return self
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Magic method to convert the entity to a string
     * @return string
     */
    public function __toString(): string
    {
        return json_encode(
            [
                'brand' => $this->brand,
                'sku' => $this->sku,
                'stockDate' => $this->stockDate->format('Y-m-d'),
                'quantity' => $this->quantity,

            ]
        );
    }
}