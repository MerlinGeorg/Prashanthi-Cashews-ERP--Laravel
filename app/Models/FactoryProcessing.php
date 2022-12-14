<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
  
class FactoryProcessing extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
  
    protected $table = 'factory_processing'; 
    protected $fillable = [
        'factory_slug', 'factory_processing_types', 'factory_processing_capacity'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom(['factory_slug', 'factory_processing_types'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function factory()
    {
        return $this->belongsTo('App\Models\Factory', 'factory_slug', 'slug')->withTrashed()->select(['slug', 'factory_name']);
    }

}