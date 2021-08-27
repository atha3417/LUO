<?php

namespace App\Models;

use App\Models\Quiz;
use App\Models\Time;
use App\Models\Type;
use App\Models\Answer;
use App\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function times()
    {
        return $this->hasMany(Time::class);
    }
}
