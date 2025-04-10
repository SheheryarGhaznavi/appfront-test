<?php

namespace App\Services\Product;

use App\DTOs\ProductDTO;
use App\Http\Requests\ProductRequest;
use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Services\File\FileServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly FileServiceInterface $fileService
    ) {}

    /**
     * Get all products
     *
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        return $this->productRepository->all();
    }

    /**
     * Find a product by ID
     *
     * @param int $id
     * @return Product|null
     */
    public function findProduct(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    /**
     * Create a new product
     *
     * @param ProductRequest $request
     * @return Product
     */
    public function createProduct(ProductRequest $request): Product
    {
        $data = ProductDTO::fromRequest($request->validated());

        if ($request->hasFile('image')) {
            $data->image = $this->fileService->storeUploadedFile($request->file('image'));
        } else {
            $data->image = $this->fileService->getDefaultImagePath();
        }

        return $this->productRepository->create((array) $data);
    }

    /**
     * Update an existing product
     *
     * @param ProductRequest $request
     * @param int $id
     * @return Product
     */
    public function updateProduct(ProductRequest $request, int $id): Product
    {
        $product = $this->findProduct($id);
        
        if (!$product) {
            throw new \RuntimeException("Product with ID {$id} not found");
        }
        
        $oldPrice = $product->price;
        $data = ProductDTO::fromRequest($request->validated());

        if ($request->hasFile('image')) {
            $data->image = $this->fileService->storeUploadedFile($request->file('image'));
        } else {
            $data->image = $product->image;
        }

        $this->productRepository->update($product, (array) $data);

        if ($oldPrice != $data->price) {
            $this->dispatchPriceChangeNotification($product, $oldPrice, $data->price);
        }

        return $product->refresh();
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        $product = $this->findProduct($id);
        
        if (!$product) {
            return false;
        }
        
        return $this->productRepository->delete($product);
    }

    /**
     * Dispatch a price change notification
     *
     * @param Product $product
     * @param float $oldPrice
     * @param float $newPrice
     * @return void
     */
    private function dispatchPriceChangeNotification(Product $product, float $oldPrice, float $newPrice): void
    {
        try {
            SendPriceChangeNotification::dispatch(
                $product,
                $oldPrice,
                $newPrice,
                config('products.notification_email')
            );
        } catch (\Exception $e) {
            Log::error('Failed to dispatch price change notification: ' . $e->getMessage());
        }
    }
}
