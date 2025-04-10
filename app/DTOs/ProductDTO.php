<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public ?string $image = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'],
            price: (float) $data['price'],
            image: $data['image'] ?? null
        );
    }
}