<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function App\Helpers\calculate_purchasing_power;
use function App\Helpers\wage_type_decode;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->category_id) {
            return [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'image' => asset('storage/' . $this->category->image),
                'total_foods' => $this->category->posts->count(),
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => asset('storage/' . $this->image),
            'total_foods' => $this->posts->count(),
        ];
    }
}
