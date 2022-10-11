<?php

namespace App\Entity;

use App\Repository\PrivacyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrivacyRepository::class)
 */
class Privacy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="text")
     */
    private $access;

    /**
     * @ORM\Column(type="text")
     */
    private $process;

    /**
     * @ORM\Column(type="text")
     */
    private $cookie;

    /**
     * @ORM\Column(type="text")
     */
    private $analytic;

    /**
     * @ORM\Column(type="text")
     */
    private $network;

    /**
     * @ORM\Column(type="text")
     */
    private $law;

    /**
     * @ORM\Column(type="text")
     */
    private $possibility;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAccess(): ?string
    {
        return $this->access;
    }

    public function setAccess(string $access): self
    {
        $this->access = $access;

        return $this;
    }

    public function getProcess(): ?string
    {
        return $this->process;
    }

    public function setProcess(string $process): self
    {
        $this->process = $process;

        return $this;
    }

    public function getCookie(): ?string
    {
        return $this->cookie;
    }

    public function setCookie(string $cookie): self
    {
        $this->cookie = $cookie;

        return $this;
    }

    public function getAnalytic(): ?string
    {
        return $this->analytic;
    }

    public function setAnalytic(?string $analytic): self
    {
        $this->analytic = $analytic;

        return $this;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(?string $network): self
    {
        $this->network = $network;

        return $this;
    }

    public function getLaw(): ?string
    {
        return $this->law;
    }

    public function setLaw(string $law): self
    {
        $this->law = $law;

        return $this;
    }

    public function getPossibility(): ?string
    {
        return $this->possibility;
    }

    public function setPossibility(string $possibility): self
    {
        $this->possibility = $possibility;

        return $this;
    }
}
