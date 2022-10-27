<?php
/**
 * @author <Akartis>
 */

namespace App\Entity\Forms;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class UserMessagesForms
{
    private ?string $content = "";

    private ArrayCollection $users;

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return UserMessagesForms
     */
    public function setContent(?string $content): UserMessagesForms
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers(): ArrayCollection
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     * @return UserMessagesForms
     */
    public function setUsers(ArrayCollection $users): UserMessagesForms
    {
        $this->users = $users;
        return $this;
    }
}
