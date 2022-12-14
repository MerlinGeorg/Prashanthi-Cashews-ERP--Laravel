<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StockyardOutwardRcn extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'slug',
        'stockyard_rcn_stock_slug',
        'factory_slug',
        'truck_reg_number',
        'dc_number',
        'ewb_number',
        'rcn_bags',
        'rcn_net_weight',
        'tare_weight',
        'status',
        'moisture_level',
        'dispatched_date_time',
        'received_date_time',
        'contact_number',
        'out_turn',
        'nut_count',
        'rejection',
        'document',
    ];

    protected $casts = [
        'dispatched_date_time' => 'datetime',
        'received_date_time' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function stockyardRcnStockDetails()
    {
        return $this->belongsTo('App\Models\StockyardRcnStock', 'stockyard_rcn_stock_slug', 'slug')
            ->select('stockyard_slug', 'lot_number', 'slug', 'account_lot_number');
    }

    public function factory()
    {
        return $this->belongsTo('App\Models\Factory', 'factory_slug', 'slug');
    }
    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->whereHas('stockyardRcnStockDetails', function ($q) {
                $q->where('stockyard_slug', Session::get('filters.stockyards'));
            });

        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }

}