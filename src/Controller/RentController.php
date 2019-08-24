<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Indisponibility;
use App\Entity\Rent;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\IndisponibilityRepository;
use App\Repository\RentRepository;
use App\Repository\UserRepository;
use App\Services\CarRentPriceCalculator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Matches /rent/*
 *
 * @Route("/rent", name="rent")
 */
class RentController extends AbstractController
{
    /** @var CarRentPriceCalculator */
    private $carRentPriceCalculator;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        CarRentPriceCalculator $carRentPriceCalculator,
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        SerializerInterface $serializer
    ) {
        $this->carRentPriceCalculator = $carRentPriceCalculator;
        $this->entityManager = $entityManager;
        $this->carRepository = new CarRepository($registry);
        $this->indisponibilityRepository = new IndisponibilityRepository($registry);
        $this->rentRepository = new RentRepository($registry);
        $this->userRepository = new UserRepository($registry);
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request)
    {
        $rent = json_decode($request->getContent(), true);

        if (isset($rent['car']) &&
            isset($rent['startingAt']) &&
            isset($rent['endingAt']) &&
            isset($rent['user'])
        ) {
            /** @var Car $car */
            $car = $this->carRepository->find($rent['car']);

            /** @var User $user */
            $user = $this->userRepository->find($rent['user']);

            if (
                null !== $car && null !== $user &&
                $this->indisponibilityRepository->isCarAvailable($car, $rent['startingAt'], $rent['endingAt'])
            ) {
                $price = $this->carRentPriceCalculator->calculate($car, $rent['startingAt'], $rent['endingAt']);

                $resultingRent = new Rent();
                $resultingRent
                    ->setCar($car)
                    ->setCreatedBy($user)
                    ->setPrice($price)
                    ->setstartingAt(new \DateTime($rent['startingAt']))
                    ->setEndingAt(new \DateTime($rent['endingAt']));

                $indisponibility = new Indisponibility();
                $indisponibility
                    ->setCar($car)
                    ->setStartingAt(new \DateTime($rent['startingAt']))
                    ->setEndingAt(new \DateTime($rent['endingAt']));

                $this->entityManager->persist($resultingRent);
                $this->entityManager->persist($indisponibility);
                $this->entityManager->flush();

                return new Response(json_encode([
                    'car' => $resultingRent->getCar()->getId(),
                    'price' => $resultingRent->getPrice(),
                    'startingAt' => $resultingRent->getStartingAt()->format('Y-m-d H:i:s'),
                    'endingAt' => $resultingRent->getEndingAt()->format('Y-m-d H:i:s'),
                ]), 200);
            } else {
                return new Response('Car not available', 400);
            }
        }

        return new Response('Bad request', 400);
    }
}
