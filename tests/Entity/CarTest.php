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
        $car->setMileage(1);
        $this->assertEquals(1, $car->getMileage());
    }

    public function testMileageFail(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $car = new Car();
        $car->setMileage(-1);
    }
}
