<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class FactoryRcnStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'factory_id',
        'factory_slug',
        'stockyard_rcn_stock_slug',
        'total_rcn_factory_stock',
        'total_rcn_bag',
        'balance_rcn_factory_stock',
        'balance_rcn_bag',
    ];

    public function outwardRcnDetails()
    {
        return $this->belongsTo('App\Models\StockyardOutwardRcn', 'outward_id', 'id');
    }

    public function stockyardRcnDetails()
    {
        return $this->belongsTo('App\Models\StockyardRcnStock', 'stockyard_rcn_stock_slug', 'slug');
    }

    public function factoryDetails()
    {
        return $this->belongsTo('App\Models\Factory', 'factory_slug', 'slug');
    }
    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->where('factory_rcn_stocks.factory_slug', Session::get('filters.factories'));

        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }
}