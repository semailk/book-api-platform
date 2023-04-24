<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource(operations: [
    new Get(),
    new Patch(),
    new GetCollection(),
    new Post(),
    new Delete(),
])]
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id,ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $isbn = null;

    #[ORM\Column(type: 'string', length: 255)]
    public string $title = '';

    #[ORM\Column(type: 'text', length: 65000)]
    public string $description = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $author = '';

    /** The publication date of this book. */
    public ?\DateTimeImmutable $publicationDate = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Review::class)]
    public iterable $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * @param string|null $isbn
     */
    public function setIsbn(?string $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getPublicationDate(): ?\DateTimeImmutable
    {
        return $this->publicationDate;
    }

    /**
     * @param \DateTimeImmutable|null $publicationDate
     */
    public function setPublicationDate(?\DateTimeImmutable $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }
}
