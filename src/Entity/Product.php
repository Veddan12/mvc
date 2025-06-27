<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * The Product class.
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $value = null;

    /**
     * Get the id for the product.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name for the product.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name for the product.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value for the product.
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * Set the value for the product.
     */
    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
