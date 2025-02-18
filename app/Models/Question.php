<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Questionnaire;
use App\Models\Option;
use App\Models\Response;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'is_required',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
