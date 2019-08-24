<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\IndisponibilityRepository")
 */
class Indisponibility
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="indisponibilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startingAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endingAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getStartingAt(): ?\DateTimeInterface
    {
        return $this->startingAt;
    }

    public function setStartingAt(\DateTimeInterface $startingAt): self
    {
        $this->startingAt = $startingAt;

        return $this;
    }

    public function getEndingAt(): ?\DateTimeInterface
    {
        return $this->endingAt;
    }

    public function setEndingAt(\DateTimeInterface $endingAt): self
    {
        $this->endingAt = $endingAt;

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
        $actualDateTime = new \DateTime();
        $actualTimestamp = $actualDateTime->getTimestamp();
        $endingAtTimestamp = $this->getEndingAt()->getTimestamp();
        $startingAtTimestamp = $this->getStartingAt()->getTimestamp();

        if ($endingAtTimestamp < $startingAtTimestamp) {
            $context->buildViolation('startingAt must be before endingAt')
                ->atPath('startingAt')
                ->addViolation();
        }

        if ($actualTimestamp > $endingAtTimestamp) {
            $context->buildViolation('endingAt must be after now')
                ->atPath('endingAt')
                ->addViolation();
        }

        if ($actualTimestamp > $startingAtTimestamp) {
            $context->buildViolation('startingAt date must be after now')
                ->atPath('startingAt')
                ->addViolation();
        }
    }
}
