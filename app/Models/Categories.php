<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Categories extends Model
{
    use HasFactory, FilterQueryString;

    protected $filters = [
        'sort',
        'like',
        'name'
    ];
    protected $table = 'categories';
    protected $fillable = ['name', 'image'];

    public function posts(): HasMany
    {
        return $this->hasMany(PostCategories::class, 'category_id', 'id');
    }
}
