<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Response;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AnalysisController extends Controller
{
    public function show(Questionnaire $questionnaire)
    {
        // Check if user is authorized to view analysis
        if (!Auth::user()->isAdmin() && !Auth::user()->isLecturer()) {
            abort(403, 'Unauthorized action.');
        }

        // If lecturer, check if they own the questionnaire
        if (Auth::user()->isLecturer() && $questionnaire->user_id !== Auth::id()) {
            abort(403, 'You can only view analysis for your own questionnaires.');
        }

        // Get all responses grouped by question
        $questions = $questionnaire->questions()->with(['options', 'responses'])->get();
        
        $analysis = [];
        foreach ($questions as $question) {
            $optionCounts = [];
            $totalResponses = 0;
            
            // Count responses for each option
            foreach ($question->options as $option) {
                $count = Response::where('question_id', $question->id)
                    ->where('selected_options', 'LIKE', '%"' . $option->id . '"%')
                    ->count();
                    
                $optionCounts[$option->id] = [
                    'text' => $option->option_text,
                    'count' => $count
                ];
                $totalResponses += $count;
            }
            
            // Calculate percentages
            foreach ($optionCounts as &$data) {
                $data['percentage'] = $totalResponses > 0 
                    ? round(($data['count'] / $totalResponses) * 100, 1) 
                    : 0;
            }
            
            $analysis[$question->id] = [
                'question' => $question->question_text,
                'options' => $optionCounts,
                'total_responses' => $totalResponses
            ];
        }

        return view('analysis.show', [
            'questionnaire' => $questionnaire,
            'analysis' => $analysis
        ]);
    }

    public function export(Request $request, Questionnaire $questionnaire)
    {
        // Check if user is authorized to export
        if (!Auth::user()->isAdmin() && !Auth::user()->isLecturer()) {
            abort(403, 'Unauthorized action.');
        }

        // If lecturer, check if they own the questionnaire
        if (Auth::user()->isLecturer() && $questionnaire->user_id !== Auth::id()) {
            abort(403, 'You can only export responses for your own questionnaires.');
        }

        $filename = Str::slug($questionnaire->title) . '_responses.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $handle = fopen('php://temp', 'r+');

        // Add headers
        fputcsv($handle, ['Respondent', 'Question', 'Selected Options', 'Submission Date']);

        // Get responses
        $responses = Response::where('questionnaire_id', $questionnaire->id)
            ->with(['user', 'question'])
            ->get()
            ->groupBy(['user_id', 'question_id']);

        foreach ($responses as $userResponses) {
            foreach ($userResponses as $questionResponses) {
                $response = $questionResponses->first();
                
                // Get selected options text
                $selectedOptionIds = json_decode($response->selected_options, true);
                $selectedOptions = Option::whereIn('id', $selectedOptionIds)
                    ->pluck('option_text')
                    ->implode(', ');

                fputcsv($handle, [
                    $response->user->name,
                    $response->question->question_text,
                    $selectedOptions,
                    $response->created_at->format('Y-m-d H:i:s')
                ]);
            }
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, $headers);
    }
}
