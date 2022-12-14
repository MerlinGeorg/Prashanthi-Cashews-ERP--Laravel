<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Office extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'office_name', 'office_short_name', 'office_reg_number', 'office_location', 'office_pincode', 'office_address_1', 'office_address_2', 'office_phone_number', 'office_state', 'updated_at', 'created_at',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('office_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeFilterbyOffice($query)
    {
        $user = $user = \Auth::user();
        if (!$user || ($user && $user->isHOStaff())) {
            return $query;
        }

        return $query->where('offices.slug', $user->work_location_slug);

    }

}