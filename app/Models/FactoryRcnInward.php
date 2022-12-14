<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FactoryRcnInward extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'slug',
        'outward_id',
        'factory_slug',
        'dc_number',
        'ewb_number',
        'rcn_bags',
        'rcn_net_weight',
        'tare_weight',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function outwardRcnDetails()
    {
        return $this->belongsTo('App\Models\StockyardOutwardRcn', 'outward_id', 'id');
    }
    public function Factory()
    {
        return $this->hasOne('App\Models\Factory', 'slug', 'factory_slug')
            ->select('factory_name', 'factory_location', 'slug');
    }

    public function scopeFilterbyOffice($query)
    {
        if (\Auth::user()->isROStaff()) {
            return $query->where('factory_rcn_inwards.factory_slug', Session::get('filters.factories'));

        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }

}