<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Questionnaire $questionnaire)
    {
        $this->authorize('respond', $questionnaire);

        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*' => 'required|exists:options,id',
        ]);

        foreach ($validated['responses'] as $questionId => $optionId) {
            Response::create([
                'user_id' => Auth::id(),
                'questionnaire_id' => $questionnaire->id,
                'question_id' => $questionId,
                'option_id' => $optionId,
            ]);
        }

        return redirect()->route('questionnaires.index')
            ->with('success', 'Response submitted successfully.');
    }

    public function index(Questionnaire $questionnaire)
    {
        $this->authorize('viewResponses', $questionnaire);

        $responses = $questionnaire->responses()
            ->with(['user', 'question', 'option'])
            ->get()
            ->groupBy('user_id');

        return view('responses.index', compact('questionnaire', 'responses'));
    }

    public function export(Questionnaire $questionnaire)
    {
        $this->authorize('viewResponses', $questionnaire);

        return response()->streamDownload(function() use ($questionnaire) {
            $handle = fopen('php://output', 'w');
            
            // Headers
            fputcsv($handle, ['Student', 'Question', 'Answer', 'Submitted At']);
            
            // Data
            foreach ($questionnaire->responses()->with(['user', 'question', 'option'])->get() as $response) {
                fputcsv($handle, [
                    $response->user->name,
                    $response->question->question_text,
                    $response->option->option_text,
                    $response->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($handle);
        }, $questionnaire->title . '_responses.csv');
    }
}
