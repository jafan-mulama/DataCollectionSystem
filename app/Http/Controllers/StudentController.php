<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionnaire;

class StudentController extends Controller
{
    public function dashboard()
    {
        $questionnaires = Questionnaire::with(['user', 'responses' => function($query) {
            $query->where('user_id', auth()->id());
        }])
        ->latest()
        ->get();

        return view('student.dashboard', compact('questionnaires'));
    }
}
