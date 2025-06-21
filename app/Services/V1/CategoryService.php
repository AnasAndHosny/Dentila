<?php

namespace App\Services\V1;

use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Repositories\V1\CategoryRepository;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index(): array
    {
        $categories = $this->categoryRepo->all();
        $categories = CategoryResource::collection($categories);
        $message = __('messages.index_success', ['class' => __('categories')]);
        $code = 200;
        return ['data' => $categories, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $category = $this->categoryRepo->create($request);
        $category = new CategoryResource($category);

        $message = __('messages.store_success', ['class' => __('category')]);
        $code = 201;
        return ['data' =>  $category, 'message' => $message, 'code' => $code];
    }

    public function show(Category $category): array
    {
        $category = new CategoryResource($category);

        $message = __('messages.show_success', ['class' => __('category')]);
        $code = 200;
        return ['data' => $category, 'message' => $message, 'code' => $code];
    }

    public function update($request, Category $category): array
    {
        $category = $this->categoryRepo->update($request, $category);
        $category = new CategoryResource($category);

        $message = __('messages.update_success', ['class' => __('category')]);
        $code = 200;
        return ['data' => $category, 'message' => $message, 'code' => $code];
    }

    public function destroy(Category $category): array
    {
        $category = $this->categoryRepo->delete($category);

        $message = __('messages.destroy_success', ['class' => __('category')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
