<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpplyeLicense extends Model
{
    use HasFactory;
    protected $fillable =[
        'license_file',
        'freelancer_id'
    ];

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }
}
