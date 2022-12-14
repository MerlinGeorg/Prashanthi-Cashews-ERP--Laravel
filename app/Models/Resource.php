<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
  
class Resource extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
  
    protected $fillable = [
        'resource_name','work_location_type'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['work_location_type','resource_name'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeGetBySlug($query,$slug){
        return $this->where('slug',$slug)->first();
    }

    public function permissions(){
        return $this->hasMany(Permission::class,'resource_slug','slug')->orderBy('name');
    }
    
    public function scopeFilterByWorkLocation($query) {
        if(\Auth::user()->isSuperAdmin() || \Auth::user()->isHOStaff())
            return $query;
        
        return $query->where('work_location_type',\Auth::user()->work_location_type);
    }
    
    public function scopeByWorkLocation($query,$work_location_type) {
        if($work_location_type == 'office')
            return $query;
        
        return $query->where('work_location_type',$work_location_type);
    }
}