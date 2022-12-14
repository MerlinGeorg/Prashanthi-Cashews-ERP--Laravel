<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactoryCuttingBoilingMap extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
  
    protected $table = 'factory_cutting_boiling_mapping';
    protected $fillable = [
        'boiling_slug', 'cutting_slug', 'aplus', 'a1', 'a2', 'b1', 'b2', 'c1', 'c2', 'd1', 'd2'
    ];
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom(['cutting_slug', 'boiling_slug'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function boiling()
    {
        return $this->belongsTo('App\Models\FactoryBoiling', 'boiling_slug', 'slug')->withTrashed()->select(['slug', 'boiling_number']);
    }

    public function cutting()
    {
        return $this->belongsTo('App\Models\FactoryCutting', 'cutting_slug', 'slug')->withTrashed()->select(['slug', 'cutting_work_number']);
    }
}
