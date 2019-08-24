<?php

namespace Tests\App\Entity;

use App\Entity\Car;
use App\Entity\Indisponibility;
use PHPUnit\Framework\TestCase;
use TypeError;

class IndisponibilityTest extends TestCase
{
    public function testIndisponibilitySuccess(): void
    {
        $startingAt = new \DateTime('now +1 day');
        $endingAt = new \DateTime('now +6 day');

        $car = new Car();

        $indisponibility = new Indisponibility();
        $indisponibility->setCar($car);
        $indisponibility->setStartingAt($startingAt);
        $indisponibility->setEndingAt($endingAt);
    }

    // public function testIndisponibilityFail(): void
    // {
    //     $this->expectException(TypeError::class);
    //     $startingAt = new \DateTime('now +1 day');
    //     $endingAt = new \DateTime('now +6 day');

    //     $indisponibility = new Indisponibility();
    //     $indisponibility->setCar(1);
    //     $indisponibility->setStartingAt($startingAt);
    //     $indisponibility->setEndingAt($endingAt);
    // }
}
