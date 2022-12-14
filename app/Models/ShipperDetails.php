<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
  
class ShipperDetails extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
  
    protected $fillable = [
        'shipper_company_name', 'shipper_location', 'shipper_contact_address_1', 'shipper_contact_address_2'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('shipper_company_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}