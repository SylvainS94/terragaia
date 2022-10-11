<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\OneToOne(targetEntity=Disponibility::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $disponibility;

    /**
     * @ORM\ManyToOne(targetEntity=Choice::class, inversedBy="bookings")
     */
    private $choices;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class)
     */
    private $services;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDisponibility(): ?Disponibility
    {
        return $this->disponibility;
    }

    public function setDisponibility(Disponibility $disponibility): self
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    public function __toString()
     {
          return $this->title;
     }

    public function getChoices(): ?Choice
    {
        return $this->choices;
    }

    public function setChoices(?Choice $choices): self
    {
        $this->choices = $choices;

        return $this;
    }

    public function getServices(): ?Service
    {
        return $this->services;
    }

    public function setServices(?Service $services): self
    {
        $this->services = $services;

        return $this;
    }


    
}
