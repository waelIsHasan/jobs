<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Profile;



class Seeker extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'fcm_token',
        'google_id',
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
        'password' => 'hashed',
    ];

    public function profile() {
        return $this->morphOne(Profile::class , 'profileable');
    }

    public function friends(){
        return $this->morphMany(Friendship::class, 'friend_one_able')
           ->unionAll($this->morphMany(Friendship::class, 'friend_two_able'));
    }

    public function reviews(){
        return $this->hasMany(Review::class)->cascadeOnDelete();
        
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
