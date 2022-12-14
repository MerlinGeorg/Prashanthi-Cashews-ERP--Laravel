<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StockyardRcnStockSplit extends Model
{
    use HasFactory, SoftDeletes;
  //   HasSlug;

    protected $table = 'stockyard_rcn_stocks_split'; 
     



    // public function getSlugOptions() : SlugOptions{
        
    //     return SlugOptions::create()
    //             ->generateSlugsFrom('lot_number')
    //             ->saveSlugsTo('slug')
    //             ->doNotGenerateSlugsOnUpdate();
    // }

    // public function stockyardDetails()
    // {
    //     return $this->belongsTo('App\Models\Stockyard', 'stockyard_slug', 'slug');
    // }

    // public function subAccount()
    // {
    //     return $this->belongsTo('App\Models\SubAccount', 'sub_account_id', 'id')
    //         ->select(array('id', 'account_state', 'account_slug','slug'));
    // }

    // public function shipperCompany()
    // {
    //     return $this->belongsTo('App\Models\ShipperDetails', 'shipper_company_slug', 'slug');
    // }

    // public function warehouse()
    // {
    //     return $this->belongsTo('App\Models\Warehouse', 'warehouse_slug', 'slug');
    // }
    // public function outwardRCN()
    // {
    //     return $this->hasMany('App\Models\StockyardOutwardRcn', 'stockyard_rcn_stock_slug', 'slug');
    // }
    // public function inwardRCN()
    // {
    //     return $this->hasMany('App\Models\StockyardInwardRcn', 'stockyard_rcn_stock_slug', 'slug');
    // }

    // public function account()
    // {
    //     return $this->hasOne('App\Models\Account','slug','account_slug')
    //         ->select(array( 'account_name', 'account_short_name','slug')); 
    // }


    // public function noofoutwardRCN(){

    //     return $this->hasMany('App\Models\StockyardOutwardRcn', 'stockyard_rcn_stock_slug', 'slug')->count();
    // }
}