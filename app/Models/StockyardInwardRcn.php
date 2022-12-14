<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StockyardInwardRcn extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'slug',
        'stockyard_slug',
        'stockyard_rcn_stock_slug',
        'truck_reg_number',
        'container_number',
        'seal_number',
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
        "dispatched_date_time" => 'datetime',
        "received_date_time" => "datetime",
    ];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function stockyardRcnStock()
    {
        return $this->belongsTo('App\Models\StockyardRcnStock', 'stockyard_rcn_stock_slug', 'slug');
    }
    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->whereHas('stockyardDetails', function ($q) {
                $q->where('office_slug', \Auth::user()->workLocation->slug);
            });
        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }
}