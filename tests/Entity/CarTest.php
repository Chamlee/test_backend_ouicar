<?php

namespace Tests\App\Entity;

use App\Entity\Car;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class CarTest extends TestCase
{
    public function testMileageSuccess(): void
    {
        $car = new Car();
        $car->setDiscretionaryMileage(49000);
        $car->setMileage(4);
        $this->assertEquals(1, $car->getMileage());
    }

    public function testMileageFail(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $car = new Car();
        $car->setDiscretionaryMileage(-1);
        $car->setMileage(3);
    }
}
