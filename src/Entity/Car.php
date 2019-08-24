<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
        $this->mileage = $this->extractMileage($mileage);

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
     * @param int $mileage
     *
     * @return int
     *
     * @throws \OutOfBoundsException
     */
    private static function extractMileage(int $mileage): int
    {
        if ($mileage < 1) {
            throw new \OutOfBoundsException();
        }
        if ($mileage < 50000) {
            return 1;
        } elseif ($mileage < 100000) {
            return 2;
        } elseif ($mileage < 150000) {
            return 3;
        } else {
            return 4;
        }
    }
}
