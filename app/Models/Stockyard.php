<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Stockyard extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'sub_account_slug', 'office_slug', 'stockyard_name', 'stockyard_short_name', 'stockyard_reg_number', 'contact_address_1', 'contact_address_2', 'stockyard_state', 'stockyard_pincode',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('stockyard_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Office', 'office_slug', 'slug')->withTrashed()->select(['slug', 'office_name']);
    }

    public function subaccount()
    {
        return $this->belongsTo('App\Models\SubAccount', 'sub_account_slug', 'slug')->select(['slug', 'account_slug']);
    }

    public function subaccountwithtrash()
    {
        return $this->belongsTo('App\Models\SubAccount', 'sub_account_slug', 'slug')->withTrashed()->select(['slug', 'account_slug']);
    }

    public function scopeFilterbyOffice($query)
    {
        $user = $user = \Auth::user();
        if (!$user || ($user && $user->isHOStaff())) {

            return $query;
        }

        $query->where('office_slug', \Auth::user()->workLocation->slug);

        return $query;

    }

    public function warehouses()
    {
        return $this->hasMany('App\Models\Warehouse', 'warehouse_stockyard_slug', 'slug');
    }

    public static function updateList()
    {
        \Session::put('filters.stockyards',
            Stockyard::filterbyOffice()->count() ? Stockyard::filterbyOffice()->pluck('slug')->toArray() : ['']
        );
    }

}