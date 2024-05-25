<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;
    protected $fillable = ['friend_one_able_id' , 'friend_one_able_type', 'friend_two_able_id','friend_two_able_type'];

    public function friend_one_able(){
        return $this->morphTo();
    }

    public function friend_two_able(){
        return $this->morphTo();
    }
}
