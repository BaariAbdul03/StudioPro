<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'slug',
        'data',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(CmsCollection::class, 'collection_id');
    }
}
