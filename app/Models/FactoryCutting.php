<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FactoryCutting extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'factory_cutting_stocks';

    protected $fillable = [
        'slug',
        'factory_slug',
        'cutting_work_number',
       // 'factory_stock_slug',
        'cutting_date_time',
        'given_rcn_bag',
        'wholes',
        'brokens',
        'piruwel',
        'rejection',
        'uncut',
        'unscoop',
        'balance_wholes',
        'balance_brokens',
        'balance_piruwel',
        'balance_cutting_selling_kernals',
        'stockyard_rcn_stock_slug',
        'cutting_type',
        'given_rcn_weight',
        'total_cutting_weight',
        'balance_cutting_weight',
        'balance_cutting_rcn_stock'
    ];

    protected $dates = ['cutting_date_time'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['factory_slug', 'cutting_work_number'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
    
    public function factory()
    {
        return $this->belongsTo('App\Models\Factory', 'factory_slug', 'slug')->withTrashed()->select(['slug', 'factory_name']);
    }

    public function cuttingMap()
    {
        return $this->hasMany('App\Models\FactoryCuttingBoilingMap', 'cutting_slug', 'slug');
    }

    public function stockyardRcnStock()
    {
        return $this->belongsTo('App\Models\StockyardRcnStock', 'stockyard_rcn_stock_slug', 'slug')->withTrashed();
    }
    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->where('factory_cutting_stocks.factory_slug', Session::get('filters.factories'));

        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }
}