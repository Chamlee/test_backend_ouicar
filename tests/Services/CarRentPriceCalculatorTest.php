<?php
namespace tests\App\Services;

use App\Services\CarRentPriceCalculator;
use App\Entity\Car;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CarRentPriceCalculatorTest extends TestCase
{
    public function testCalculatePrice(): void
    {
        $car = new Car();
        $car->setPriceDay1(3)
            ->setPriceDay3(2)
            ->setPriceDay7(1);

        //1 day
        $calculator = new CarRentPriceCalculator();
        $this->assertSame(3,
            $calculator->calculate(
                $car,
                '2019-08-08T08:00:00-00:00',
                '2019-08-08T09:00:00-00:00'
            )
        );

        //3 days 3+3+2
        $calculator = new CarRentPriceCalculator();
        $this->assertSame(8,
            $calculator->calculate(
                $car,
                '2019-08-08T08:00:00-00:00',
                '2019-08-10T08:00:00-00:00'
            )
        );

        //7 days 3+3+2+2+2+2+1
        $calculator = new CarRentPriceCalculator();
        $this->assertSame(15,
            $calculator->calculate(
                $car,
                '2019-08-08T08:00:00-00:00',
                '2019-08-14T08:00:00-00:00'
            )
        );
    }

    public function testInvalidDate(): void
    {
        $this->expectException(Exception::class);

        $car = (new Car())
            ->setPriceDay1(3)
            ->setPriceDay3(2)
            ->setPriceDay7(1);

        $calculator = new CarRentPriceCalculator();
        $calculator->calculate(
            $car,
            '2019-08-08T21:00:00-00:00',
            '2019-08-07T21:00:00-00:00'
        );
    }
}
