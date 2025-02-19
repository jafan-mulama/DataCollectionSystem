<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\Option;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'questionnaire_id',
        'question_id',
        'selected_options'
    ];

    protected $casts = [
        'selected_options' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function getSelectedOptionsAttribute($value)
    {
        $optionIds = json_decode($value, true);
        return Option::whereIn('id', $optionIds)->get();
    }
}
