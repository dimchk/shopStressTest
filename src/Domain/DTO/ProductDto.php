<?php

namespace App\Domain\DTO;

use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\Serializer\Attribute\SerializedName;

class ProductDto
{
    public function __construct(#[SerializedName('productId')] private readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
