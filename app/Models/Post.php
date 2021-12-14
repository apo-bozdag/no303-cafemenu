<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Post extends Model
{
    use HasFactory, HasApiTokens, FilterQueryString;

    protected $filters = [
        'sort',
        'greater',
        'greater_or_equal',
        'less',
        'less_or_equal',
        'between',
        'not_between',
        'in',
        'like', 'category_id'
    ];
    protected $fillable = [
        'title', 'slug', 'image', 'price'
    ];
    protected $table = 'posts';


    public function category_id($query, $value)
    {
        return $query->whereHas('categories', function ($q) use ($value) {
            $value = explode(',', $value);
            return $q->whereIn('category_id', $value);
        });
    }

    protected function get_field_and_values($value): array
    {
        $exploded = explode(',', $value);
        $field = array_shift($exploded);

        return [$field, (key_exists(0, $exploded) ? $exploded[0] : null)];
    }

    public function categories(): HasMany
    {
        return $this->hasMany(PostCategories::class, 'post_id', 'id');
    }
}
