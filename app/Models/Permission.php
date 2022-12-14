<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Permission extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name', 'resource_slug', 'guard_name', 'work_location_type',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['resource_slug', 'name'])
            ->saveSlugsTo('slug');
    }

    public function resource()
    {
        return $this->belongsTo('App\Models\Resource', 'resource_slug', 'slug')->withTrashed()->select(['id', 'slug', 'resource_name']);
    }

}