<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return CategoryCollection
     */
    public function index(Request $request): CategoryCollection
    {
        $paginate = $request->get('limit') ?: 15;
        $categories = Categories::filter()->paginate($paginate)->appends(request()->except('page'));
        return new CategoryCollection($categories);
    }
}
