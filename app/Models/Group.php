<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['nama_group'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
}

