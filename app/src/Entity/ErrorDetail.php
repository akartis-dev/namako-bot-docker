<?php

namespace App\Entity;

use App\Repository\ErrorDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ErrorDetailRepository::class)]
class ErrorDetail
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $detail = "";

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $origin = "";

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $searchTerm = "";

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @param string|null $origin
     * @return ErrorDetail
     */
    public function setOrigin(?string $origin): ErrorDetail
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchTerm(): ?string
    {
        return $this->searchTerm;
    }

    /**
     * @param string|null $searchTerm
     * @return ErrorDetail
     */
    public function setSearchTerm(?string $searchTerm): ErrorDetail
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }
}
