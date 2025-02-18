<?php

namespace App\Policies;

use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionnairePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Questionnaire $questionnaire): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isLecturer()) {
            return $questionnaire->user_id === $user->id;
        }

        return $questionnaire->is_published && 
            ($questionnaire->expires_at === null || $questionnaire->expires_at > now());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isLecturer() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Questionnaire $questionnaire): bool
    {
        return $user->isAdmin() || ($user->isLecturer() && $questionnaire->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Questionnaire $questionnaire): bool
    {
        return $user->isAdmin() || ($user->isLecturer() && $questionnaire->user_id === $user->id);
    }

    /**
     * Determine whether the user can respond to the model.
     */
    public function respond(User $user, Questionnaire $questionnaire): bool
    {
        return $user->isStudent() && 
            $questionnaire->is_published && 
            ($questionnaire->expires_at === null || $questionnaire->expires_at > now());
    }

    /**
     * Determine whether the user can view responses of the model.
     */
    public function viewResponses(User $user, Questionnaire $questionnaire): bool
    {
        return $user->isAdmin() || ($user->isLecturer() && $questionnaire->user_id === $user->id);
    }
}
