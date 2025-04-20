<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Product|null
     */
    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Product must be associated with an inventory record.')]
    private ?Product $product = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[Assert\NotNull(message: 'Quantity cannot be null.')]
    #[Assert\PositiveOrZero(message: 'Quantity must be zero or a positive value.')]
    private ?int $quantity = null;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'Last updated date cannot be null.')]
    #[Assert\Type(
        type: DateTimeInterface::class,
        message: 'Last updated must be a valid date and time.'
    )]
    private ?DateTimeInterface $lastUpdated = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastUpdated(): ?DateTimeInterface
    {
        return $this->lastUpdated;
    }

    /**
     * @param DateTimeInterface $lastUpdated
     * @return $this
     */
    public function setLastUpdated(DateTimeInterface $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int|null", 'productId' => "\App\Entity\Product|null", 'quantity' => "int|null", 'lastUpdated' => "string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'productId' => $this->getProduct(),
            'quantity' => $this->getQuantity(),
            'lastUpdated' => $this->getLastUpdated()->format('Y-m-d H:i:s')
        ];
    }

}