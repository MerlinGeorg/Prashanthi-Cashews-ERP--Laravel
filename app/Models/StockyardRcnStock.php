<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StockyardRcnStock extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'stockyard_slug',
        'sub_account_id',
        'shipper_company_slug',
        'lot_number',
        'rcn_mark',
        'be_number',
        'bl_number',
        'invoice_number',
        'bl_despatched_rcn_weight',
        'bl_despatched_rcn_bags',
        'out_turn',
        'nut_count',
        'rejection',
        'warehouse_slug',
        'balance_rcn_stock',
        'balance_rcn_bag',
        'account_lot_number',
        'account_slug',
        'total_containers',
        'type'
    ];

    public function getSlugOptions(): SlugOptions
    {

        return SlugOptions::create()
            ->generateSlugsFrom('lot_number')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function stockyardDetails()
    {
        return $this->belongsTo('App\Models\Stockyard', 'stockyard_slug', 'slug');
    }

    public function subAccount()
    {
        return $this->belongsTo('App\Models\SubAccount', 'sub_account_id', 'id')
            ->select(array('id', 'account_state', 'account_slug', 'slug'));
    }

    public function shipperCompany()
    {
        return $this->belongsTo('App\Models\ShipperDetails', 'shipper_company_slug', 'slug');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_slug', 'slug');
    }
    public function outwardRCN()
    {
        return $this->hasMany('App\Models\StockyardOutwardRcn', 'stockyard_rcn_stock_slug', 'slug');
    }
    public function inwardRCN()
    {
        return $this->hasMany('App\Models\StockyardInwardRcn', 'stockyard_rcn_stock_slug', 'slug');
    }

    public function account()
    {
        return $this->hasOne('App\Models\Account', 'slug', 'account_slug')
            ->select(array('account_name', 'account_short_name', 'slug'));
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
    // public function noofoutwardRCN(){

    //     return $this->hasMany('App\Models\StockyardOutwardRcn', 'stockyard_rcn_stock_slug', 'slug')->count();
    // }
}