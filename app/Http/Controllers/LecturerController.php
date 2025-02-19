<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionnaire;

class LecturerController extends Controller
{
    public function dashboard()
    {
        $questionnaires = Questionnaire::where('user_id', auth()->id())
            ->withCount(['questions', 'responses'])
            ->latest()
            ->get();

        return view('lecturer.dashboard', compact('questionnaires'));
    }
}
