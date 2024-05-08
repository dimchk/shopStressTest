<?php

namespace App\Domain\Repository;

use App\Domain\DTO\CartDto;
use App\Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function create(CartDto $cartDto):Order;

}
