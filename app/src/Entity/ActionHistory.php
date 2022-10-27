<?php

namespace App\Entity;

use App\Repository\ActionHistoryRepository;
use App\Services\Constants;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ActionHistoryRepository::class)]
class ActionHistory
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'actionHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $type;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $url;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $status;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $quality;

    public function __construct()
    {
        $this->status = Constants::STATUS_INIT;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return ActionHistory
     */
    public function setStatus(?string $status): ActionHistory
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuality(): ?string
    {
        return $this->quality;
    }

    /**
     * @param string|null $quality
     * @return ActionHistory
     */
    public function setQuality(?string $quality): ActionHistory
    {
        $this->quality = $quality;
        return $this;
    }
}
