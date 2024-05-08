<?php

namespace App\Domain\Repository;

use App\Domain\DTO\CartDto;
use App\Domain\Entity\Product;

interface CartRepositoryInterface
{
    public function addToCart(Product $product): void;

    public function getActiveCart(): CartDto;

}
