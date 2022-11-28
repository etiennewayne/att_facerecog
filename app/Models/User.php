<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username', 'lname', 'fname', 'mname', 'suffix', 'sex',
        'province', 'city', 'barangay', 'street',
        'contact_no',
        'role','salary_level_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function descriptions(){
        return $this->hasMany(Descriptor::class, 'user_id', 'user_id');
    }

    public function salary_level(){
        return $this->hasOne(SalaryLevel::class, 'salary_level_id', 'salary_level_id');
    }

}
