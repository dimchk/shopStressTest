<?php

namespace App\Infrostructure\Repository;

use App\Domain\DTO\CartDto;
use App\Domain\Entity\Order;
use App\Domain\Repository\OrderRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function create(CartDto $cartDto): Order
    {
        $order = new Order();
        $order->setProducts($cartDto->getSerializedProducts())->setTotalAmount(100)->setSessionId($cartDto->getSessionId());
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
        return $order;
    }
}
