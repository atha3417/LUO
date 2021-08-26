<?php

namespace App\Models;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Choice extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
