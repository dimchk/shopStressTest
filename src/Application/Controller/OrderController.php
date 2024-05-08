<?php

namespace App\Application\Controller;

use App\Domain\UseCase\CartUseCase;
use App\Domain\UseCase\OrderUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(private OrderUseCase $orderUseCase)
    {
    }

    #[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout()
    {
        try{
            $order = $this->orderUseCase->checkout();
            return new Response("Order created with id {$order->getId()}");
        }
        catch (\DomainException $exception){
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
