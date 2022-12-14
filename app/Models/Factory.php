<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Factory extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'factory_of', 'factory_sub_account_slug', 'factory_office_slug', 'factory_name', 'factory_short_name', 'factory_reg_number', 'factory_location', 'factory_power_allocation', 'factory_contact_address_1', 'factory_contact_address_2', 'factory_state', 'factory_pincode',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('factory_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function factoryProcessing()
    {
        return $this->hasMany('App\Models\FactoryProcessing', 'factory_slug', 'slug');
    }

    public function subaccount()
    {
        return $this->belongsTo('App\Models\SubAccount', 'factory_sub_account_slug', 'slug')->select(['slug', 'account_slug']);
    }

    public function subaccountwithtrash()
    {
        return $this->belongsTo('App\Models\SubAccount', 'factory_sub_account_slug', 'slug')->withTrashed()->select(['slug', 'account_slug']);
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Office', 'factory_office_slug', 'slug')->withTrashed()->select(['slug', 'office_name']);
    }

    public function scopeFilterbyOffice($query)
    {
        $user = $user = \Auth::user();
        if (!$user || ($user && $user->isHOStaff())) {
            return $query;
        }

        return $query->where('factories.factory_office_slug', $user->work_location_slug);

    }

    public static function updateList()
    {
        \Session::put('filters.factories',
            Factory::filterbyOffice()->count() ? Factory::filterbyOffice()->pluck('slug')->toArray() : ['']
        );
    }
}