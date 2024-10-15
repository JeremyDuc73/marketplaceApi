<?php

namespace App\Entity;

use App\Repository\PurchasedApiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchasedApiRepository::class)]
class PurchasedApi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchasedApis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $linkToProfile = null;

    #[ORM\Column(length: 255)]
    private ?string $ApiName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkToProfile(): ?Profile
    {
        return $this->linkToProfile;
    }

    public function setLinkToProfile(?Profile $linkToProfile): static
    {
        $this->linkToProfile = $linkToProfile;

        return $this;
    }

    public function getApiName(): ?string
    {
        return $this->ApiName;
    }

    public function setApiName(string $ApiName): static
    {
        $this->ApiName = $ApiName;

        return $this;
    }
}
