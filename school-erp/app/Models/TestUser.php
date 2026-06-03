<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestUser extends Model
{
    //
    protected $fillable = [
        'name',
        'age',
        'phone',
        'image'
    ];

    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : null;
    }
}
