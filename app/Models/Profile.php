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
    'brief',
    'home_place' ,
    'work_place' ,
    'birthday' ,
    'email',
    'phone_no',
    'work_as',
    'foundation',
    'overview',
    'ceo',
    'projects',
    'company_name',
    'friends',
    'profileable_id' ,
    'profileable_type'
];
    public function profileable(){
        return $this->morphTo();
    }
}
