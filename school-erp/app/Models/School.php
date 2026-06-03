<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    protected $fillable = [
    'board',
    'schoolName',
    'directorName',
    'schoolContact',
    'email',
    'website',
    'schoolAdd',
    'established_year',
    'code',
    'city',
    'pincode',
    'schoolLogo',
    ];
}
