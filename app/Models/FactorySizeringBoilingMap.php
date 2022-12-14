<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FactorySizeringBoilingMap extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'factory_sizering_boiling_mapping';
    protected $fillable = [
        'boiling_slug', 'sizering_slug', 'aplus', 'a1', 'a2', 'b1', 'b2', 'c1', 'c2', 'd1', 'd2',
    ];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['boiling_slug', 'sizering_slug'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function boiling()
    {
        return $this->belongsTo('App\Models\FactoryBoiling', 'boiling_slug', 'slug')->withTrashed()->select(['slug', 'boiling_number']);
    }

    public function sizering()
    {
        return $this->belongsTo('App\Models\FactorySizering', 'sizering_slug', 'slug')->withTrashed()->select(['slug', 'sizering_number']);
    }
}