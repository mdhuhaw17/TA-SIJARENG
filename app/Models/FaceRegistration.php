<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dataset_path',
        'total_images'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
