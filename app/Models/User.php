<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;




class User extends Authenticatable implements BannableContract, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;
    use Bannable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    #=======================================================================================#
    #			                    To allow insert in table                              	#
    #=======================================================================================#
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_verifications',
        'national_id',
        'num_dep',
        'has_insurance',
        'has_stage',
        'password',
        'gender',
        'profile_image',
        'birth_date',
        'weight_kg',
        'total_sessions',
        'remain_session',
        'last_login_at',
        'city_id',
        'category_id',
        'gym_id',
        'updated_at',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
    public function TrainingSessions()
    {
        return $this->belongsToMany(TrainingSession::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function courseAttendances()
    {
        return $this->hasManyThrough(CourseAttendance::class, Participation::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'participations')
            ->withPivot(['status', 'participated_at'])
            ->withTimestamps();
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'weight_kg' => 'decimal:2',
    ];

    public function payments()
    {
        return $this->hasMany(\App\Models\UserPayment::class);
    }

    public function belts()
    {
        return $this->hasMany(\App\Models\Belt::class);
    }

    public function documents()
    {
        return $this->hasMany(\App\Models\UserDocument::class);
    }

    public function groups()
    {
        return $this->belongsToMany(\App\Models\Group::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(\App\Models\EventRegistration::class);
    }

    public function getProfileImageFileAttribute()
    {
        if ($this->attributes['profile_image']) {
            return $this->attributes['profile_image'];
        } else {
            return 'avatar.png';
        }
    }


}
