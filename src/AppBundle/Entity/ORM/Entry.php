<?php

namespace AppBundle\Entity\ORM;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
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
     */
    private $gameName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $gameCRC;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $coreName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
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
     */
    private function __construct()
    {
        $this->createdAt = new \DateTime();
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
        string $username,
        string $coreName,
        string $coreVersion,
        string $gameName,
        string $gameCRC,
        bool $hasPassword = false
    ): Entry {
        $entry = new self();
        $entry->setUsername($username);
        $entry->setCoreName($coreName);
        $entry->setCoreVersion($coreVersion);
        $entry->setGameName($gameName);
        $entry->setGameCRC($gameCRC);
        $entry->setHasPassword($hasPassword);

        return $entry;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort(string $port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getGameName(): string
    {
        return $this->gameName;
    }

    /**
     * @param string $gameName
     */
    public function setGameName(string $gameName)
    {
        $this->gameName = $gameName;
    }

    /**
     * @return string
     */
    public function getGameCRC(): string
    {
        return $this->gameCRC;
    }

    /**
     * @param string $gameCRC
     */
    public function setGameCRC(string $gameCRC)
    {
        $this->gameCRC = $gameCRC;
    }

    /**
     * @return string
     */
    public function getCoreName(): string
    {
        return $this->coreName;
    }

    /**
     * @param string $coreName
     */
    public function setCoreName(string $coreName)
    {
        $this->coreName = $coreName;
    }

    /**
     * @return string
     */
    public function getCoreVersion(): string
    {
        return $this->coreVersion;
    }

    /**
     * @param string $coreVersion
     */
    public function setCoreVersion(string $coreVersion)
    {
        $this->coreVersion = $coreVersion;
    }

    /**
     * @return bool
     */
    public function hasPassword(): bool
    {
        return $this->hasPassword;
    }

    /**
     * @param bool $hasPassword
     */
    public function setHasPassword(bool $hasPassword)
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
