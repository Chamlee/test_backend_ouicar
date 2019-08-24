<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=1, max=4)
     */
    private $mileage;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceDay1;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceDay3;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceDay7;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Indisponibility", mappedBy="car")
     */
    private $indisponibilities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rent", mappedBy="car")
     */
    private $rents;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive
     */
    private $discretionaryMileage;

    public function __construct()
    {
        $this->indisponibilities = new ArrayCollection();
        $this->rents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): self
    {
        $this->mileage = $this->extractMileage($this->discretionaryMileage);

        return $this;
    }

    public function getPriceDay1(): ?int
    {
        return $this->priceDay1;
    }

    public function setPriceDay1(int $priceDay1): self
    {
        $this->priceDay1 = $priceDay1;

        return $this;
    }

    public function getPriceDay3(): ?int
    {
        return $this->priceDay3;
    }

    public function setPriceDay3(int $priceDay3): self
    {
        $this->priceDay3 = $priceDay3;

        return $this;
    }

    public function getPriceDay7(): ?int
    {
        return $this->priceDay7;
    }

    public function setPriceDay7(int $priceDay7): self
    {
        $this->priceDay7 = $priceDay7;

        return $this;
    }

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     *
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if ($this->getPriceDay3() > $this->getPriceDay1()) {
            $context->buildViolation('Price day 3 must be inferior to price day 1')
                ->atPath('priceDay3')
                ->addViolation();
            return;
        }

        if ($this->getPriceDay7() > $this->getPriceDay3()) {
            $context->buildViolation('Price day 7 must be inferior to day 3')
                ->atPath('priceDay7')
                ->addViolation();
            return;
        }
    }

    /**
     * @param int $discretionaryMileage
     *
     * @return int
     *
     * @throws \OutOfBoundsException
     */
    private static function extractMileage($discretionaryMileage): int
    {
        if ($discretionaryMileage < 0) {
            throw new \OutOfBoundsException();
        }
        if ($discretionaryMileage < 50000) {
            return 1;
        } elseif ($discretionaryMileage < 100000) {
            return 2;
        } elseif ($discretionaryMileage < 150000) {
            return 3;
        } else {
            return 4;
        }
    }

    /**
     * @return Collection|Indisponibility[]
     */
    public function getIndisponibilities(): Collection
    {
        return $this->indisponibilities;
    }

    public function addIndisponibility(Indisponibility $indisponibility): self
    {
        if (!$this->indisponibilities->contains($indisponibility)) {
            $this->indisponibilities[] = $indisponibility;
            $indisponibility->setCar($this);
        }

        return $this;
    }

    public function removeIndisponibility(Indisponibility $indisponibility): self
    {
        if ($this->indisponibilities->contains($indisponibility)) {
            $this->indisponibilities->removeElement($indisponibility);
            // set the owning side to null (unless already changed)
            if ($indisponibility->getCar() === $this) {
                $indisponibility->setCar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getRents(): Collection
    {
        return $this->rents;
    }

    public function addRent(Rent $rent): self
    {
        if (!$this->rents->contains($rent)) {
            $this->rents[] = $rent;
            $rent->setCar($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rents->contains($rent)) {
            $this->rents->removeElement($rent);
            // set the owning side to null (unless already changed)
            if ($rent->getCar() === $this) {
                $rent->setCar(null);
            }
        }

        return $this;
    }

    public function getDiscretionaryMileage(): ?int
    {
        return $this->discretionaryMileage;
    }

    public function setDiscretionaryMileage(?int $discretionaryMileage): self
    {
        $this->discretionaryMileage = $discretionaryMileage;

        return $this;
    }
}
