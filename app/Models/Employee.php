<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Employee extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'gender',
        'religion',
        'dob',
        'job_type',
        'job_category',
        'nationality',
        'employee_no',
        'aadhar_no',
        'identification_file',
        'work_location_type',
        'work_location_slug',
        'email',
        'address_line_1',
        'address_line_2',
        'city',
        'district',
        'state',
        'pincode',
        'mobile',
        'whatsapp',
        'join_date',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'join_date' => 'datetime',
        'dob' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeActiveUsers($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePendingUsers($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInactiveUsers($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeFilterbyOffice($query, $work_location_type = '')
    {
        if ($work_location_type) {
            $query->where('work_location_type', $work_location_type);
        }

        if (\Auth::user()->isROStaff()) {

            switch ($work_location_type) {
                case "stockyard":
                    $query->join('stockyards', function ($join) {
                        return $join->on('stockyards.slug', 'work_location_slug')
                            ->where('office_slug', \Auth::user()->workLocation->slug);
                    });
                    break;
                case "factory":
                    $query->join('factories', function ($join) {
                        return $join->on('factories.slug', 'work_location_slug')
                            ->where('factory_office_slug', \Auth::user()->workLocation->slug);
                    });
                    break;
                case "package":
                    $query->join('packaging_centers', function ($join) {
                        return $join->on('packaging_centers.slug', 'work_location_slug')
                            ->where('package_center_office_slug', \Auth::user()->workLocation->slug);
                    });
                    break;
                default:
                    $query->where('work_location_slug', \Auth::user()->workLocation->slug);
                    break;
            }
        }
        return $query;

    }

    public function workLocation()
    {
        if ($this->work_location_type == 'office') {
            return $this->belongsTo(Office::class, 'work_location_slug', 'slug')->select('*', 'office_name as name');
        } else if ($this->work_location_type == 'factory') {
            return $this->belongsTo(Factory::class, 'work_location_slug', 'slug')->select('*', 'factory_name as name');
        } else if ($this->work_location_type == 'package') {
            return $this->belongsTo(PackageCenter::class, 'work_location_slug', 'slug')->select('*', 'package_center_name as name');
        }

        if ($this->work_location_type == 'stockyard') {
            return $this->belongsTo(Stockyard::class, 'work_location_slug', 'slug')->select('*', 'stockyard_name as name');
        }

    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'job_category', 'slug');
    }
}