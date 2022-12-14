<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FactoryBorma extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'factory_borma_stocks';

    protected $fillable = [
        'borma_work_number',
        'borma_work_date_time',
        'factory_slug',
        'stockyard_rcn_stock_slug',
        'wholes',
        'brokens',
        'piruwal',
        'balance_wholes',
        'balance_brokens',
        'balance_piruwal',
    ];

    protected $dates = ['borma_work_date_time'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['factory_slug', 'borma_work_number'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function factoryDetails()
    {
        return $this->belongsTo('App\Models\Factory', 'factory_slug', 'slug');
    }

    public function stockyardRcnStock()
    {
        return $this->belongsTo('App\Models\StockyardRcnStock', 'stockyard_rcn_stock_slug', 'slug')->withTrashed();
    }

    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->where('factory_borma_stocks.factory_slug', Session::get('filters.factories'));

        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }
}