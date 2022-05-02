<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    protected $casts = [
        'authors' => 'array',
    ];

    // public function setAuthorsAttribute($value)
    // {
    //     $this->attributes['authors'] = implode(',',$value);
    // }
}
