<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Beacon;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $QRCode;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="location")
     */
    private $Events;

    public function getId()
    {
        return $this->id;
    }

    public function getBeacon(): ?int
    {
        return $this->Beacon;
    }

    public function setBeacon(int $Beacon): self
    {
        $this->Beacon = $Beacon;

        return $this;
    }

    public function getQRCode(): ?string
    {
        return $this->QRCode;
    }

    public function setQRCode(?string $QRCode): self
    {
        $this->QRCode = $QRCode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->Events;
    }
}
