<?php

namespace AppBundle\Entity\ORM;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Entry.
 *
 * @ORM\Entity
 */
class Entry
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $port;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $gameName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $gameCRC;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $coreName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $coreVersion;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $hasPassword;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Entry constructor.
     *
     * @param string $username
     * @param string $coreName
     * @param string $coreVersion
     * @param string $gameName
     * @param string $gameCRC
     * @param bool   $hasPassword
     */
    private function __construct($username, $coreName, $coreVersion, $gameName, $gameCRC, bool $hasPassword = false)
    {
        $this->createdAt = new \DateTime();

        $this->username    = $username;
        $this->coreName    = $coreName;
        $this->coreVersion = $coreVersion;
        $this->gameName    = $gameName;
        $this->gameCRC     = $gameCRC;
        $this->hasPassword = $hasPassword;
    }

    /**
     * Constructor used for submissions.
     *
     * @param string $username
     * @param string $coreName
     * @param string $coreVersion
     * @param string $gameName
     * @param string $gameCRC
     * @param bool   $hasPassword
     *
     * @return Entry
     */
    static public function fromSubmission(
        $username,
        $coreName,
        $coreVersion,
        $gameName,
        $gameCRC,
        bool $hasPassword = false
    ): Entry {
        return new self($username, $coreName, $coreVersion, $gameName, $gameCRC, $hasPassword);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getGameName()
    {
        return $this->gameName;
    }

    /**
     * @param string $gameName
     */
    public function setGameName($gameName)
    {
        $this->gameName = $gameName;
    }

    /**
     * @return string
     */
    public function getGameCRC()
    {
        return $this->gameCRC;
    }

    /**
     * @param string $gameCRC
     */
    public function setGameCRC($gameCRC)
    {
        $this->gameCRC = $gameCRC;
    }

    /**
     * @return string
     */
    public function getCoreName()
    {
        return $this->coreName;
    }

    /**
     * @param string $coreName
     */
    public function setCoreName($coreName)
    {
        $this->coreName = $coreName;
    }

    /**
     * @return string
     */
    public function getCoreVersion()
    {
        return $this->coreVersion;
    }

    /**
     * @param string $coreVersion
     */
    public function setCoreVersion($coreVersion)
    {
        $this->coreVersion = $coreVersion;
    }

    /**
     * @return bool
     */
    public function hasPassword()
    {
        return $this->hasPassword;
    }

    /**
     * @param bool $hasPassword
     */
    public function setHasPassword($hasPassword)
    {
        $this->hasPassword = $hasPassword;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
