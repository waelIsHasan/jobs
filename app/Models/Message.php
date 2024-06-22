<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id' , 'message' , 'senderable_id' , 'senderable_type'];

    public function senderable(){
        return $this->morphTo();
    }
   
    public function chat(){
        return $this->belongsTo(Chat::class);
    }
}
