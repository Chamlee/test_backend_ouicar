<?php

namespace App\Services;

use App\Entity\Car;
use InvalidArgumentException;

class CarRentPriceCalculator
{
    /**
     * @param Car       $car
     * @param string $startingAt
     * @param string $endingAt
     *
     * @return int
     */
    public function calculate(Car $car, $startingAt, $endingAt): int
    {
        $endingAt = new \DateTime($endingAt);
        $startingAt = new \DateTime($startingAt);

        if ($startingAt > $endingAt) {
            throw new \Exception(InvalidArgumentException::class);
        }

        $nbDays = (int) $endingAt->diff($startingAt)->format('%a') + 1;

        if ($nbDays <= 2) {
            $price = $car->getPriceDay1() * $nbDays;
        } elseif ($nbDays <= 6) {
            $price = $car->getPriceDay1() * 2 + $car->getPriceDay3() * ($nbDays - 2);
        } else {
            $price = $car->getPriceDay1() * 2 + $car->getPriceDay3() * 4 + $car->getPriceDay7() * ($nbDays - 6);
        }

        return $price;
    }
}
