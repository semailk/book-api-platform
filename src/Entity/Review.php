<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource(operations: [
    new Get(),
    new Patch(),
    new GetCollection(),
    new Post(),
    new Delete()
])]
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id,ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    public int $rating = 0;

    #[ORM\Column(type: 'string', length: 255)]
    public string $body = '';

    #[ORM\Column(type: 'string', length: 255)]
    public string $author = '';

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy:  'reviews')]
    public ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
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

    /**
     * @return Book|null
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book|null $book
     */
    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }
}
