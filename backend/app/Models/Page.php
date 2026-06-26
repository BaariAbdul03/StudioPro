<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'project_id', 'title', 'slug', 'components', 'styles', 'html', 'css', 'meta', 'sort_order', 'is_published'
    ];

    protected $casts = [
        'components' => 'array',
        'styles' => 'array',
        'meta' => 'array',
        'is_published' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function versions()
    {
        return $this->hasMany(PageVersion::class);
    }
}
