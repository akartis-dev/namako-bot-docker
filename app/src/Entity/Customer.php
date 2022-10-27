<?php

namespace App\Entity;

use App\Entity\Message\UserMessages;
use App\Repository\CustomerRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private ?string $facebookId;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $firstName;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $lastName;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $profilePic;

    #[Groups(['message:get'])]
    private ?string $name = "";

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: ActionHistory::class)]
    private $actionHistories;

    #[ORM\ManyToMany(targetEntity: UserMessages::class, mappedBy: 'customer')]
    private $customerMessages;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: UserMessages::class)]
    private $messages;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->actionHistories = new ArrayCollection();
        $this->customerMessages = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profilePic;
    }

    public function setProfilePic(?string $profilePic): self
    {
        $this->profilePic = $profilePic;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return Collection<int, ActionHistory>
     */
    public function getActionHistories(): Collection
    {
        return $this->actionHistories;
    }

    public function addActionHistory(ActionHistory $actionHistory): self
    {
        if (!$this->actionHistories->contains($actionHistory)) {
            $this->actionHistories[] = $actionHistory;
            $actionHistory->setCustomer($this);
        }

        return $this;
    }

    public function removeActionHistory(ActionHistory $actionHistory): self
    {
        if ($this->actionHistories->removeElement($actionHistory)) {
            // set the owning side to null (unless already changed)
            if ($actionHistory->getCustomer() === $this) {
                $actionHistory->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return sprintf("%s %s", $this->firstName, $this->lastName);
    }

    /**
     * @return Collection<int, UserMessages>
     */
    public function getCustomerMessages(): Collection
    {
        return $this->customerMessages;
    }

    public function addCustomerMessage(UserMessages $customerMessage): self
    {
        if (!$this->customerMessages->contains($customerMessage)) {
            $this->customerMessages[] = $customerMessage;
            $customerMessage->addCustomer($this);
        }

        return $this;
    }

    public function removeCustomerMessage(UserMessages $customerMessage): self
    {
        if ($this->customerMessages->removeElement($customerMessage)) {
            $customerMessage->removeCustomer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserMessages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(UserMessages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setSender($this);
        }

        return $this;
    }

    public function removeMessage(UserMessages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSender() === $this) {
                $message->setSender(null);
            }
        }

        return $this;
    }
}
