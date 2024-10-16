<?php

namespace App\Entity;

use App\Repository\PurchasedApiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'purchasedApi')]
    private Collection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setPurchasedApi($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getPurchasedApi() === $this) {
                $orderItem->setPurchasedApi(null);
            }
        }

        return $this;
    }
}
