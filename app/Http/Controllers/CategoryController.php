<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        return view('category.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $term = $request->get('term');
        $categories = Categories::query()
            ->select('*', 'name as value')
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orderBy('name')
            ->get()->each(function ($i) {
                $i->makeVisible('current_wage');
            });

        return response()->json($categories);
    }
}
