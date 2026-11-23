<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $touches = ['project'];

    protected $fillable = [
        'project_id', 'title', 'slug', 'components', 'styles', 'project_data', 'html', 'css', 'meta', 'sort_order',
    ];

    protected $appends = ['public_url'];

    protected $casts = [
        'components' => 'array',
        'styles' => 'array',
        'project_data' => 'array',
        'meta' => 'array',
        'is_published' => 'boolean',
    ];

    public static function draftRules(): array
    {
        $structuredData = [
            'nullable', 'array',
            static function ($attribute, $value, $fail) {
                if (strlen(json_encode($value, JSON_THROW_ON_ERROR)) > 1_048_576) {
                    $fail('The :attribute exceeds 1 MB.');
                }
            },
        ];

        return [
            'html' => 'nullable|string|max:262144',
            'css' => 'nullable|string|max:131072',
            'components' => $structuredData,
            'styles' => $structuredData,
            'project_data' => $structuredData,
            'meta' => 'nullable|array:seoTitle,seoDescription,ogTitle,ogDescription,headCode,bodyCode',
            'meta.seoTitle' => 'nullable|string|max:255',
            'meta.seoDescription' => 'nullable|string|max:1000',
            'meta.ogTitle' => 'nullable|string|max:255',
            'meta.ogDescription' => 'nullable|string|max:1000',
            'meta.headCode' => 'nullable|string|max:65536',
            'meta.bodyCode' => 'nullable|string|max:65536',
        ];
    }

    protected function publicUrl(): Attribute
    {
        return Attribute::get(fn () => $this->is_published && $this->public_id
            ? rtrim(config('studio.public_url') ?: config('app.url'), '/').'/p/'.$this->public_id
            : null);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function versions()
    {
        return $this->hasMany(PageVersion::class);
    }
}
