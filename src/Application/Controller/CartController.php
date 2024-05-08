<?php

namespace App\Application\Controller;

use App\Domain\DTO\ProductDto;
use App\Domain\UseCase\CartUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(protected CartUseCase $cartUseCase)
    {
    }

    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(#[MapRequestPayload] ProductDto $productDto)
    {
        try {
            $this->cartUseCase->addToCart($productDto);
            return new Response("Congratulations! The product has been successfully added to your cart.");
        } catch (\DomainException $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
