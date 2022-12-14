<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
    protected $table = 'warehouses';
    protected $fillable = [
        'warehouse_name', 'warehouse_stockyard_slug', 'warehouse_account_slug', 'warehouse_account_state', 'slug',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('warehouse_name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function subaccount()
    {
        return $this->belongsTo('App\Models\SubAccount', 'warehouse_account_slug', 'slug')->select(['slug', 'account_slug']);
    }

    public function stockyard()
    {
        return $this->belongsTo('App\Models\Stockyard', 'warehouse_stockyard_slug', 'slug')->withTrashed()->select(['id', 'slug', 'stockyard_name']);
    }

}