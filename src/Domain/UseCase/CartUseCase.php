<?php

namespace App\Domain\UseCase;

use App\Domain\DTO\ProductDto;
use App\Domain\Repository\CartRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;

class CartUseCase
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository
    ) {
    }

    public function addToCart(ProductDto $productDto):void
    {
        $product = $this->productRepository->findById($productDto->getId());
        if ($product && $product->getQuantity() > 0) {
            $this->cartRepository->addToCart($product);
            return;
        }
        throw new \DomainException('Oops! It seems this product is currently out of stock.');
    }
}
