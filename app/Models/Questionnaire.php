<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Question;
use App\Models\Response;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'is_published',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Check if a user has responded to this questionnaire
     *
     * @param User $user
     * @return bool
     */
    public function userHasResponded(User $user)
    {
        return $this->responses()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the response for a specific user
     *
     * @param User $user
     * @return Response|null
     */
    public function getResponseForUser(User $user)
    {
        return $this->responses()->where('user_id', $user->id)->first();
    }

    /**
     * Get the total number of responses
     *
     * @return int
     */
    public function getResponsesCount()
    {
        return $this->responses()->count();
    }
}
