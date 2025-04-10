<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRate\ExchangeRateServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly ExchangeRateServiceInterface $exchangeRateService
    ) {}

    /**
     * Display a listing of products
     *
     * @return View
     */
    public function index(): View
    {
        $products = $this->productService->getAllProducts();
        $exchangeRate = $this->exchangeRateService->getRate();

        return view('products.list', compact('products', 'exchangeRate'));
    }

    /**
     * Display the specified product
     *
     * @param int $productId
     * @return View
     */
    public function show(int $productId): View
    {
        $product = $this->productService->findProduct($productId);

        if (!$product) {
            abort(404);
        }

        $exchangeRate = $this->exchangeRateService->getRate();

        return view('products.show', compact('product', 'exchangeRate'));
    }
}
