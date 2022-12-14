<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
  
class Account extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
  
    protected $fillable = [
        'account_name', 'account_short_name'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('account_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function subAccounts()
    {
        return $this->hasMany('App\Models\SubAccount', 'account_slug', 'slug');
    }
}