<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
  
class SubAccount extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
    protected $table = 'subaccounts'; 
    protected $fillable = [
        'account_slug', 'account_address_1', 'account_address_2', 'account_state', 'account_gst'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['account_slug', 'account_state'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_slug', 'slug')->withTrashed()->select(['id', 'slug', 'account_name']);
    }

}