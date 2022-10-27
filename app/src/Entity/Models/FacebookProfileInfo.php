<?php
/**
 * @author <Akartis>
 */
namespace App\Entity\Models;

class FacebookProfileInfo
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $profilePic,
        private int $facebookId)
    {
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return FacebookProfileInfo
     */
    public function setFirstName(string $firstName): FacebookProfileInfo
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return FacebookProfileInfo
     */
    public function setLastName(string $lastName): FacebookProfileInfo
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfilePic(): string
    {
        return $this->profilePic;
    }

    /**
     * @param string $profilePic
     * @return FacebookProfileInfo
     */
    public function setProfilePic(string $profilePic): FacebookProfileInfo
    {
        $this->profilePic = $profilePic;
        return $this;
    }

    /**
     * @return int
     */
    public function getFacebookId(): int
    {
        return $this->facebookId;
    }

    /**
     * @param int $facebookId
     * @return FacebookProfileInfo
     */
    public function setFacebookId(int $facebookId): FacebookProfileInfo
    {
        $this->facebookId = $facebookId;
        return $this;
    }
}
