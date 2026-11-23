<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Asset extends Model
{
    protected $fillable = [
        'project_id',
        'filename',
        'path',
        'mime_type',
        'size',
        'dimensions',
    ];

    protected $casts = [
        'dimensions' => 'array',
    ];

    protected $appends = ['url'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => "/api/projects/{$this->project_id}/assets/{$this->id}/file",
        );
    }
}
