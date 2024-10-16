<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $ofOrder = null;

    /**
     * @var Collection<int, CreatedApi>
     */
    #[ORM\ManyToMany(targetEntity: CreatedApi::class, inversedBy: 'orderItems')]
    private Collection $CreatedApi;

    public function __construct()
    {
        $this->CreatedApi = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOfOrder(): ?Order
    {
        return $this->ofOrder;
    }

    public function setOfOrder(?Order $ofOrder): static
    {
        $this->ofOrder = $ofOrder;

        return $this;
    }

    /**
     * @return Collection<int, CreatedApi>
     */
    public function getCreatedApi(): Collection
    {
        return $this->CreatedApi;
    }

    public function addCreatedApi(CreatedApi $createdApi): static
    {
        if (!$this->CreatedApi->contains($createdApi)) {
            $this->CreatedApi->add($createdApi);
        }

        return $this;
    }

    public function removeCreatedApi(CreatedApi $createdApi): static
    {
        $this->CreatedApi->removeElement($createdApi);

        return $this;
    }
}
