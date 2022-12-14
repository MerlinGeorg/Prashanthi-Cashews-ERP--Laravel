<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class PackageCenter extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'package_center_sub_account_slug', 'package_center_office_slug', 'package_center_name', 'package_center_short_name', 'package_center_reg_number', 'package_center_power_allocation', 'package_center_location', 'package_center_state', 'package_center_contact_address_1', 'package_center_contact_address_2', 'package_center_pincode',
    ];
    protected $table = 'packaging_centers';

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('package_center_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Office', 'package_center_office_slug', 'slug')->withTrashed()->select(['slug', 'office_name']);
    }

    public function subaccount()
    {
        return $this->belongsTo('App\Models\SubAccount', 'package_center_sub_account_slug', 'slug')->select(['slug', 'account_slug']);
    }

    public function subaccountwithtrash()
    {
        return $this->belongsTo('App\Models\SubAccount', 'package_center_sub_account_slug', 'slug')->withTrashed()->select(['slug', 'account_slug']);
    }

    public function scopeFilterbyOffice($query)
    {
        $user = $user = \Auth::user();
        if (!$user || ($user && $user->isHOStaff())) {
            return $query;
        }

        return $query->where('packaging_centers.package_center_office_slug', $user->work_location_slug);

    }

    public static function updateList()
    {
        \Session::put('filters.package-centers',
            PackageCenter::filterbyOffice()->count() ? PackageCenter::filterbyOffice()->pluck('slug')->toArray() : ['']
        );
    }
}