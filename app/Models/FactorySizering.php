<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FactorySizering extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'factory_sizering_stocks';
    protected $fillable = [
        'factory_slug', 'rcn_weight', 'rcn_bag', 'factory_stock_slug', 'sizering_number', 'sizering_date_time',
        'aplus_total_weight', 'a1_total_weight', 'a2_total_weight', 'b1_total_weight', 'b2_total_weight',
        'c1_total_weight', 'c2_total_weight', 'd1_total_weight', 'd2_total_weight',
        'foreign_matter_total_weight', 'aplus_balance_weight',
        'a1_balance_weight', 'a2_balance_weight', 'b1_balance_weight', 'b2_balance_weight',
        'c1_balance_weight', 'c2_balance_weight', 'd1_balance_weight', 'd2_balance_weight',
        'total_sizering_rcn_weight', 'balance_sizering_rcn_weight',
        'total_sizering_rcn_bag', 'balance_sizering_rcn_bag',
        'total_output_weight', 'total_sizering_rcn_stock', 'balance_sizering_rcn_stock',
    ];
    protected $dates = ['sizering_date_time'];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['factory_stock_slug', 'sizering_number'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function factory()
    {
        return $this->belongsTo('App\Models\Factory', 'factory_slug', 'slug')->withTrashed()->select(['slug', 'factory_name']);
    }

    public function boilingMap()
    {
        return $this->hasMany('App\Models\FactorySizeringBoilingMap', 'sizering_slug', 'slug');
    }

    public function stockyardRcnStock()
    {
        return $this->belongsTo('App\Models\StockyardRcnStock', 'factory_stock_slug', 'slug')->withTrashed();
    }

    public function scopeAvailableSizering($query)
    {
        return $query->where('total_output_weight', '>', 0);
    }
    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->where('factory_sizering_stocks.factory_slug', Session::get('filters.factories'));

        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }
}