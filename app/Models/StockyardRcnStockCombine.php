<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class StockyardRcnStockCombine extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'stockyard_rcn_stocks_combine';

    protected $fillable = [


        'slug',
        'lot_number',
        'account',
        'stockyard',

    ];


    public function getSlugOptions(): SlugOptions
    {

        return SlugOptions::create()
            ->generateSlugsFrom('lot_number')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

}
