<?php

namespace App\Entity;

use App\Repository\LibraryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * The Library class.
 */
#[ORM\Entity(repositoryClass: LibraryRepository::class)]
class Library
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    private ?string $isbn = null;

    #[ORM\Column(length: 255)]
    private ?string $bookcover = null;

    /**
     * Get the id for the book.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the id of the book.
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the book title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the book title.
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the author of the book.
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Set the author of the book.
     */
    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the isbn of the book.
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * Set the isbn of the book.
     */
    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get the bookcover.
     */
    public function getBookcover(): ?string
    {
        return $this->bookcover;
    }

    /**
     * Set the bookcover.
     */
    public function setBookcover(string $bookcover): static
    {
        $this->bookcover = $bookcover;

        return $this;
    }
}
