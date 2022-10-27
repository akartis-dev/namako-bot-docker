<?php

namespace App\Entity\Message;

use App\Entity\Customer;
use App\Repository\Message\UserMessagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserMessagesRepository::class)]
class UserMessages
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['message:get'])]
    private int $id;

    #[ORM\Column(type: 'text')]
    #[Groups(['message:get'])]
    private ?string $content;

    #[ORM\ManyToMany(targetEntity: Customer::class, inversedBy: 'customerMessages')]
    #[ORM\JoinColumn(nullable: true)]
    private $customer;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $allCustomer = false;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'messages')]
    #[Groups(['message:get'])]
    private ?Customer $sender;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->customer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customer->contains($customer)) {
            $this->customer[] = $customer;
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        $this->customer->removeElement($customer);

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAllCustomer(): ?bool
    {
        return $this->allCustomer;
    }

    public function isAllCustomer(): ?bool
    {
        return $this->allCustomer;
    }


    /**
     * @param bool $allCustomer
     * @return UserMessages
     */
    public function setAllCustomer(bool $allCustomer): UserMessages
    {
        $this->allCustomer = $allCustomer;

        return $this;
    }

    public function getSender(): ?Customer
    {
        return $this->sender;
    }

    public function setSender(?Customer $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    #[Groups(['message:get'])]
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
