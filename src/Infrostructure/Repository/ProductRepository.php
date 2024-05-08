<?php

namespace App\Infrostructure\Repository;

use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(string $id): ?Product
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function decreaseQuantity(Product $product, int $amount = 1): Product
    {
        $product->setQuantity($product->getQuantity() - $amount);
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
        return $product;
    }
}
