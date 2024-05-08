<?php

namespace App\Domain\DTO;

class CartDto
{
    /**
     * @var ProductDto[]
     */
    private array $products = [];
    private string $serializedProducts = '';

    public function __construct(private string $sessionId, array $productsId)
    {
        $this->serializedProducts = json_encode(array_map(function ($id) {
            return ["id" => $id];
        }, $productsId));

        $this->products = array_map(function ($productsId) {
            return new ProductDto($productsId);
        }, $productsId);

    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getSerializedProducts(): string
    {
        return $this->serializedProducts;
    }
}
