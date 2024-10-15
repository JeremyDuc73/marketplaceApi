<?php

namespace App\Entity;

use App\Repository\CreatedApiRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreatedApiRepository::class)]
class CreatedApi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;
    
    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $requestAmountPerSale = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $apiKey = null;

    #[ORM\Column(length: 255)]
    private ?string $docLink = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRequestAmountPerSale(): ?int
    {
        return $this->requestAmountPerSale;
    }

    public function setRequestAmountPerSale(int $requestAmountPerSale): static
    {
        $this->requestAmountPerSale = $requestAmountPerSale;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getDocLink(): ?string
    {
        return $this->docLink;
    }

    public function setDocLink(string $docLink): static
    {
        $this->docLink = $docLink;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
