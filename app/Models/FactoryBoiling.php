<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FactoryBoiling extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'factory_boiling_stocks';
    protected $fillable = [
        'factory_slug', 'boiling_number', 'boiling_date_time', 'aplus_total_weight', 'a1_total_weight', 'a2_total_weight', 'b1_total_weight', 'b2_total_weight', 'c1_total_weight', 'c2_total_weight', 'd1_total_weight', 'd2_total_weight', 'total_boiling_weight', 'aplus_balance_weight', 'a1_balance_weight', 'a2_balance_weight', 'b1_balance_weight', 'b2_balance_weight', 'c1_balance_weight', 'c2_balance_weight', 'd1_balance_weight', 'd2_balance_weight', 'balance_boiling_weight', 'stockyard_rcn_stock_slug','balance_boiling_rcn_stock'
    ];
    protected $dates = ['boiling_date_time'];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['factory_slug', 'boiling_number'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function factory()
    {
        return $this->belongsTo('App\Models\Factory', 'factory_slug', 'slug')->withTrashed()->select(['slug', 'factory_name']);
    }

    public function boilingMap()
    {
        return $this->hasMany('App\Models\FactorySizeringBoilingMap', 'boiling_slug', 'slug');
    }

    public function stockyardRcnStock()
    {
        return $this->belongsTo('App\Models\StockyardRcnStock', 'stockyard_rcn_stock_slug', 'slug')->withTrashed();
    }
    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->where('factory_boiling_stocks.factory_slug', Session::get('filters.factories'));

        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }

    public function scopeAvailableBoiling($query)
    {
        return $query->where('balance_boiling_weight', '>', 0);
    }
}