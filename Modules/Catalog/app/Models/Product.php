<?php

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $price_cents
 * @property int $stock
 * @property bool $is_published
 * @property-read Category|null $category
 */
#[Fillable([
    'category_id',
    'name',
    'slug',
    'description',
    'price_cents',
    'stock',
    'is_published',
])]
class Product extends Model
{
    protected $table = 'catalog_products';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /** @param Builder<Product> $query */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /** @param Builder<Product> $query */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->published()->where('stock', '>', 0);
    }

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'stock' => 'integer',
            'is_published' => 'boolean',
        ];
    }
}
