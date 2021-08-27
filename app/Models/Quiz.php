<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Answer;
use App\Models\Choice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
