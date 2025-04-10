<?php

namespace App\Console\Commands;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Console\Command;

class UpdateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductServiceInterface $productService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        $product = $this->productRepository->find($id);

        if (!$product) {
            $this->error("Product with ID {$id} not found.");
            return 1;
        }

        $data = [];
        if ($this->option('name')) {
            $data['name'] = $this->option('name');
            if (empty($data['name']) || trim($data['name']) == '') {
                $this->error("Name cannot be empty.");
                return 1;
            }
            if (strlen($data['name']) < 3) {
                $this->error("Name must be at least 3 characters long.");
                return 1;
            }
        } else {
            $data['name'] = $product->name;
        }

        $data['description'] = $this->option('description') ?: $product->description;
        $data['price'] = $this->option('price') ?: $product->price;

        // Create a DTO with the updated data
        $productDTO = new ProductDTO(
            name: $data['name'],
            description: $data['description'],
            price: (float) $data['price'],
            image: $product->image
        );

        $oldPrice = $product->price;
        $newPrice = (float) $data['price'];

        if ($oldPrice != $newPrice || $product->name != $data['name'] || $product->description != $data['description']) {
            // Update the product using the repository
            $this->productRepository->update($product, (array) $productDTO);

            $this->info("Product updated successfully.");

            // Check if price has changed
            if ($oldPrice != $newPrice) {
                $this->info("Price changed from {$oldPrice} to {$newPrice}.");

                // Use the product service to handle price change notifications
                try {
                    // We're using reflection to access the private method for demonstration purposes
                    $reflectionMethod = new \ReflectionMethod($this->productService, 'dispatchPriceChangeNotification');
                    $reflectionMethod->setAccessible(true);
                    $reflectionMethod->invoke($this->productService, $product, $oldPrice, $newPrice);

                    $this->info("Price change notification dispatched to " . config('products.notification_email'));
                } catch (\Exception $e) {
                    $this->error("Failed to dispatch price change notification: " . $e->getMessage());
                }
            }
        } else {
            $this->info("No changes provided. Product remains unchanged.");
        }

        return 0;
    }
}
