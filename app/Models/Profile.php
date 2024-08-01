<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
    'image',
    'Bio',
    'home_place' ,
    'work_place' ,
    'birthday' ,
    
    'profileable_id' ,
    'profileable_type'
];
    public function profileable(){
        return $this->morphTo();
    }
}
