<?php

namespace App\Services\Product;

use App\DTOs\ProductDTO;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface
{
    /**
     * Get all products
     *
     * @return Collection
     */
    public function getAllProducts(): Collection;

    /**
     * Find a product by ID
     *
     * @param int $id
     * @return Product|null
     */
    public function findProduct(int $id): ?Product;

    /**
     * Create a new product
     *
     * @param ProductRequest $request
     * @return Product
     */
    public function createProduct(ProductRequest $request): Product;

    /**
     * Update an existing product
     *
     * @param ProductRequest $request
     * @param int $id
     * @return Product
     */
    public function updateProduct(ProductRequest $request, int $id): Product;

    /**
     * Delete a product
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool;
}
