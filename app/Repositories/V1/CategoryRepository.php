<?php

namespace App\Repositories\V1;

use App\Models\Category;

class CategoryRepository
{
    public function all()
    {
        return Category::latest()->get();
    }

    public function create($request)
    {
        return Category::create([
            'name' => $request['name'],
        ]);
    }

    public function update($request, Category $category)
    {
        $category->update([
            'name' => $request['name'] ?? $category['name'],
        ]);
        return $category;
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }

    public function getPlans(Category $category): mixed
    {
        return $category->treatmentPlans()->latest()->get();
    }
}
