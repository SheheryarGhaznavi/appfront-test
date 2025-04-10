<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
        private readonly ProductServiceInterface $productService
    ) {}

    /**
     * Display the login page
     *
     * @return View
     */
    public function loginPage(): View
    {
        return view('login');
    }

    /**
     * Handle the login request
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        if ($this->authService->attempt($request->except('_token'))) {
            return redirect()->route('admin.product');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    /**
     * Handle the logout request
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('login');
    }

    /**
     * Display a list of all products
     *
     * @return View
     */
    public function products(): View
    {
        $products = $this->productService->getAllProducts();
        return view('admin.products', compact('products'));
    }

    /**
     * Show the form for creating a new product
     *
     * @return View
     */
    public function createProduct(): View
    {
        return view('admin.add_product');
    }

    /**
     * Store a newly created product
     *
     * @param ProductRequest $request
     * @return RedirectResponse
     */
    public function storeProduct(ProductRequest $request): RedirectResponse
    {
        $this->productService->createProduct($request);
        return redirect()->route('admin.product')->with('success', 'Product added successfully');
    }

    /**
     * Show the form for editing a product
     *
     * @param int $id
     * @return View|RedirectResponse
     */
    public function editProduct(int $id): View|RedirectResponse
    {
        $product = $this->productService->findProduct($id);

        if (!$product) {
            return redirect()->route('admin.product')->with('error', 'Product not found');
        }

        return view('admin.edit_product', compact('product'));
    }

    /**
     * Update the specified product
     *
     * @param ProductRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function updateProduct(ProductRequest $request, int $id): RedirectResponse
    {
        try {
            $this->productService->updateProduct($request, $id);
            return redirect()->route('admin.product')->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.product')->with('error', $e->getMessage());
        }
    }

    /**
     * Delete the specified product
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteProduct(int $id): RedirectResponse
    {
        if ($this->productService->deleteProduct($id)) {
            return redirect()->route('admin.product')->with('success', 'Product deleted successfully');
        }

        return redirect()->route('admin.product')->with('error', 'Product not found');
    }
}
