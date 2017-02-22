<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Entry.
 *
 * @ORM\Entity
 */
class Entry implements \Serializable, \JsonSerializable
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
     * @Assert\Ip()
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
     *
     * @Assert\Type("bool")
     */
    private $hasPassword;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * Entry constructor.
     *
     * @param bool   $hasPassword
     * @param string $port
     */
    private function __construct(bool $hasPassword = false, string $port = '55435')
    {
        $this->createdAt   = new \DateTime();
        $this->hasPassword = $hasPassword;
        $this->port        = $port;
    }

    /**
     * Alternative constructor.
     *
     * @param $username
     * @param $coreName
     * @param $coreVersion
     * @param $gameName
     * @param $gameCRC
     *
     * @return Entry
     */
    static public function fromSubmission($username, $coreName, $coreVersion, $gameName, $gameCRC)
    {
        $entry = new self();
        $entry->setUsername($username);
        $entry->setCoreName($coreName);
        $entry->setCoreVersion($coreVersion);
        $entry->setGameName($gameName);
        $entry->setGameCRC($gameCRC);

        return $entry;
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

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Casts object to array.
     */
    public function serialize()
    {
        return serialize([
            'username'    => $this->getUsername(),
            'ip'          => $this->getIp(),
            'port'        => $this->getPort(),
            'coreName'    => $this->getCoreName(),
            'coreVersion' => $this->getCoreVersion(),
            'gameName'    => $this->getGameName(),
            'gameCRC'     => $this->getGameCRC(),
            'createdAt'   => $this->getCreatedAt()->format(\DateTime::ATOM),
        ]);
    }

    /**
     * Casts array to object.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->setUsername($data['username']);
        $this->setIp($data['ip']);
        $this->setPort($data['port']);
        $this->setCoreName($data['coreName']);
        $this->setCoreVersion($data['coreVersion']);
        $this->setGameName($data['gameName']);
        $this->setGameCRC($data['gameCRC']);
        $this->setCreatedAt(\DateTime::createFromFormat(\DateTime::ATOM, $data['createdAt']));
    }

    /**
     * Uses camelCase.
     *
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'username'    => $this->getUsername(),
            'ip'          => $this->getIp(),
            'port'        => $this->getPort(),
            'coreName'    => $this->getCoreName(),
            'coreVersion' => $this->getCoreVersion(),
            'gameName'    => $this->getGameName(),
            'gameCRC'     => $this->getGameCRC(),
            'createdAt'   => $this->getCreatedAt()->format(\DateTime::ATOM),
        ];
    }
}
