<?php

namespace App\Entity;

use App\Repository\DisponibilityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisponibilityRepository::class)
 */
class Disponibility
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $date_slot;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hour_slot;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dispo = false; // Par defaut dispo est false

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSlot(): ?string
    {
        return $this->date_slot;
    }

    public function setDateSlot(string $date_slot): self
    {
        $this->date_slot = $date_slot;

        return $this;
    }

    public function getHourSlot(): ?\DateTimeInterface
    {
        return $this->hour_slot;
    }

    public function setHourSlot(\DateTimeInterface $hour_slot): self
    {
        $this->hour_slot = $hour_slot;

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }

    // public function __toString()
    // {
    //     return $this->name;
    // }

}
