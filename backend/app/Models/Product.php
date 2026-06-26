<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'title', 'slug', 'description', 'price', 
        'compare_at_price', 'sku', 'inventory_quantity', 'inventory_status', 'images', 'category'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
