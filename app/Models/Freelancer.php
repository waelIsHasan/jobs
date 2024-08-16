<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Profile;
use App\Models\Friendship;
use App\Models\Save;

class Freelancer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'fcm_token',
        'email_verified_at'
    ];

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
       // 'password' => 'hashed',
    ];

    public function profile() {
        return $this->morphOne(Profile::class , 'profileable');
    }


    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function services(){
        return $this->hasMany(Service::class);
    }

    public function friends(){
        return $this->morphMany(Friendship::class, 'friend_one_able')
           ->unionAll($this->morphMany(Friendship::class, 'friend_two_able'));
    }

    public function saves(){
        return $this->hasMany(Save::class);
    }
    

    public function License()
    {
        return $this->hasOne(EmpplyeLicense::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

   
    
}
