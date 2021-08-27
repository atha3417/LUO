<?php

namespace App\Models;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tests()
    {
        return $this->hasMany(Quiz::class);
    }
}
