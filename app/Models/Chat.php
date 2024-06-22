<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = ['senderable_id' , 'senderable_type' , 'receiverable_id' , 'receiverable_type'];

    public function senderable(){
        return $this->morphTo();
    }

    public function receiverable(){
        return $this->morphTo();
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }


}
