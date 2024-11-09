<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 * @ORM\Table(name="vehicle")
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id", nullable=false)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=20)
     */
    private $registrationNumber;

    /**
     * @ORM\Column(type="string", length=17, unique=true)
     * @Assert\NotBlank()
     */
    private $vin;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $clientEmail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $clientAddress;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCurrentlyRented = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentLocationAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(string $registrationNumber): self
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(string $vin): self
    {
        $this->vin = $vin;

        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(string $clientEmail): self
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }

    public function getClientAddress(): ?string
    {
        return $this->clientAddress;
    }

    public function setClientAddress(string $clientAddress): self
    {
        $this->clientAddress = $clientAddress;

        return $this;
    }

    public function isCurrentlyRented(): ?bool
    {
        return $this->isCurrentlyRented;
    }

    public function setIsCurrentlyRented(bool $isCurrentlyRented): self
    {
        $this->isCurrentlyRented = $isCurrentlyRented;

        return $this;
    }

    public function getCurrentLocationAddress(): ?string
    {
        return $this->currentLocationAddress;
    }

    public function setCurrentLocationAddress(?string $currentLocationAddress): self
    {
        $this->currentLocationAddress = $currentLocationAddress;

        return $this;
    }
}
