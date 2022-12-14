<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
  
class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, HasSlug;
  
    protected $fillable = [
        'name','guard_name','work_location_type'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeGetBySlug($query,$slug) {
        return $query->where('slug',$slug)->first();
    }

    public function scopeFilterByWorkLocation($query) {
        if(\Auth::user()->isSuperAdmin() || \Auth::user()->isHOStaff())
            return $query;
        
        return $query->where('work_location_type',\Auth::user()->work_location_type);
    }
    
    public function workLocationType(){
        $work_location_types = \Config::get('constants.work_location_types');
        return $work_location_types[$this->work_location_type] ?? '';
           
    }

}