<?php

namespace App\Infrostructure\Repository;

use App\Domain\DTO\CartDto;
use App\Domain\Entity\Product;
use App\Domain\Repository\CartRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function addToCart(Product $product): void
    {
        $session = $this->requestStack->getSession();
        $cart = json_decode($session->get('cart')) ?? [];
        if (!in_array($product->getId(), $cart)) {
            $cart[] = $product->getId();
        }
        $session->set('cart', json_encode($cart));
    }

    public function getActiveCart(): CartDto
    {
        $session = $this->requestStack->getSession();
        return new CartDto($session->getId(), json_decode($session->get('cart')) ?? []);
    }
}
