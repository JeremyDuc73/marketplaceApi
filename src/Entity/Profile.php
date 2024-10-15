<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ofUser = null;

    /**
     * @var Collection<int, PurchasedApi>
     */
    #[ORM\OneToMany(targetEntity: PurchasedApi::class, mappedBy: 'linkToProfile')]
    private Collection $purchasedApis;

    public function __construct()
    {
        $this->purchasedApis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfUser(): ?User
    {
        return $this->ofUser;
    }

    public function setOfUser(User $ofUser): static
    {
        $this->ofUser = $ofUser;

        return $this;
    }

    /**
     * @return Collection<int, PurchasedApi>
     */
    public function getPurchasedApis(): Collection
    {
        return $this->purchasedApis;
    }

    public function addPurchasedApi(PurchasedApi $purchasedApi): static
    {
        if (!$this->purchasedApis->contains($purchasedApi)) {
            $this->purchasedApis->add($purchasedApi);
            $purchasedApi->setLinkToProfile($this);
        }

        return $this;
    }

    public function removePurchasedApi(PurchasedApi $purchasedApi): static
    {
        if ($this->purchasedApis->removeElement($purchasedApi)) {
            // set the owning side to null (unless already changed)
            if ($purchasedApi->getLinkToProfile() === $this) {
                $purchasedApi->setLinkToProfile(null);
            }
        }

        return $this;
    }
}
