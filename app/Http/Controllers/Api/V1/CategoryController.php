<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\V1\CategoryService;
use App\Traits\HandlesServiceResponse;
use App\Http\Requests\V1\Category\StoreCategoryRequest;
use App\Http\Requests\V1\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use HandlesServiceResponse;

    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->categoryService->index()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->categoryService->store($request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->categoryService->show($category)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->categoryService->update($request, $category)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->categoryService->destroy($category)
        );
    }

    public function showPlans(Category $category): JsonResponse
    {
        return $this->handleService(
            fn() =>
            $this->categoryService->showPlans($category)
        );
    }
}
