<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVersion extends Model
{
    protected $fillable = [
        'page_id',
        'version_label',
        'components',
        'styles',
        'project_data',
        'html',
        'css',
        'meta',
    ];

    protected $casts = [
        'components' => 'array',
        'styles' => 'array',
        'project_data' => 'array',
        'meta' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
