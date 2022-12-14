<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasSlug, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'gender',
        'religion',
        'dob',
        'email',
        'qualification',
        'experiences',
        'employee_no',
        'aadhar_no',
        'nationality',
        'identification_file',
        'job_type',
        'user_group_id',
        'work_location_type',
        'work_location_slug',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'mobile',
        'whatsapp',
        'join_date',
        'username',
        'password',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        return $query->where('status', 'active')->exceptSuperAdmin();
    }

    public function scopePendingUsers($query)
    {
        return $query->where('status', 'pending')->exceptSuperAdmin();
    }

    public function scopeInactiveUsers($query)
    {
        return $query->where('status', 'inactive')->exceptSuperAdmin();
    }

    public function scopeFilterbyOffice($query)
    {
        $query->exceptSuperAdmin();
        if (\Auth::user()->isROStaff()) {
            return $query->where('work_location_slug', \Auth::user()->workLocation->slug);
        } else if (\Auth::user()->isHOStaff()) {
            return $query;
        }

    }

    public function isHOStaff()
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->workLocation) {
            return false;
        }

        return $this->work_location_type == 'office' ?
        $this->workLocation->office_type == 'HO' : false;
    }

    public function isROStaff()
    {

        if (!$this->workLocation) {
            return false;
        }

        return $this->work_location_type == 'office' ?
        $this->workLocation->office_type == 'RO' : false;
    }

    public function workLocation()
    {
        if ($this->work_location_type == 'office') {
            return $this->belongsTo(Office::class, 'work_location_slug', 'slug')->select('*', 'office_name as name');
        } else if ($this->work_location_type == 'factory') {
            return $this->belongsTo(Factory::class, 'work_location_slug', 'slug')->select('*', 'factory_name as name');
        } else if ($this->work_location_type == 'package') {
            return $this->belongsTo(PackageCenter::class, 'work_location_slug', 'slug')->select('*', 'package_center_name as name');
        } else {
            return $this->belongsTo(Stockyard::class, 'work_location_slug', 'slug')->select('*', 'stockyard_name as name');
        }

    }

    public function getRoleIds()
    {
        return $this->roles()->pluck('id')->toArray();
    }

    public function isSuperAdmin()
    {
        return $this->user_group == "super-admin";
    }

    public function scopeExceptSuperAdmin($query)
    {
        return $query->WhereNull('user_group')->orWhere('user_group', '!=', 'super-admin');
    }

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'user_group', 'slug');
    }

}