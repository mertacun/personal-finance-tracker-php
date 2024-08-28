<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json($category, 201);
    }

    public function destroy(Category $category)
    {
        if ($category->transactions()->exists()) {
            return response()->json(['error' => 'Category cannot be deleted because it has associated transactions'], 400);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
