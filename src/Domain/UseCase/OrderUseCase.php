<?php

namespace App\Domain\UseCase;

use App\Domain\DTO\ProductDto;
use App\Domain\Entity\Order;
use App\Domain\Repository\CartRepositoryInterface;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

class OrderUseCase
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
        protected ProductRepositoryInterface $productRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function checkout(): Order
    {
        try {
            $store = new FlockStore();
            $factory = new LockFactory($store);
            $cart = $this->cartRepository->getActiveCart();
            $lock = $factory->createLock('productId');
            if (empty($cart->getProducts())) {
                throw new \DomainException("Cart is empty");
            }
            /**
             * @var ProductDto $productDto
             */
            foreach ($cart->getProducts() as $productDto) {

                $lock->acquire(true);
                $product = $this->productRepository->findById($productDto->getId());
                if (!$product || $product->getQuantity() <= 0) {
                    throw new \DomainException("Product not found or is out of stock");
                }
                $product->setQuantity($product->getQuantity() - 1);
                $this->entityManager->persist($product);
            }
            $order = $this->orderRepository->create($cart);
            $this->entityManager->flush();
            $lock->release();
            return $order;
        } catch (\Exception $e)  {
            $lock->release();
            $this->entityManager->clear();
            throw new \DomainException($e->getMessage());
        }
    }

}
