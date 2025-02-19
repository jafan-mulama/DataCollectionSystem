<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->isStudent()) {
            $questionnaires = Questionnaire::where('is_published', true)
                ->whereNull('expires_at')
                ->orWhere('expires_at', '>', now())
                ->get();
        } else {
            $questionnaires = Auth::user()->questionnaires;
        }

        return view('questionnaires.index', compact('questionnaires'));
    }

    public function create()
    {
        $this->authorize('create', Questionnaire::class);
        return view('questionnaires.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Questionnaire::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*' => 'required|string|max:255'
        ]);

        $questionnaire = Auth::user()->questionnaires()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_published' => $validated['is_published'] ?? false,
            'published_at' => isset($validated['is_published']) && $validated['is_published'] ? now() : null,
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            $question = $questionnaire->questions()->create([
                'question_text' => $questionData['text'],
                'is_required' => true,
                'order' => $index,
            ]);

            foreach ($questionData['options'] as $optionIndex => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'order' => $optionIndex,
                ]);
            }
        }

        return redirect()->route('questionnaires.edit', $questionnaire)
            ->with('success', 'Questionnaire ' . ($questionnaire->is_published ? 'published' : 'saved as draft') . ' successfully.');
    }

    public function show(Questionnaire $questionnaire)
    {
        $this->authorize('view', $questionnaire);
        return view('questionnaires.show', compact('questionnaire'));
    }

    public function edit(Questionnaire $questionnaire)
    {
        $this->authorize('update', $questionnaire);
        return view('questionnaires.edit', compact('questionnaire'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $this->authorize('update', $questionnaire);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if (isset($validated['is_published']) && $validated['is_published']) {
            $validated['published_at'] = now();
        }

        $questionnaire->update($validated);

        return redirect()->route('questionnaires.show', $questionnaire)
            ->with('success', 'Questionnaire updated successfully.');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $this->authorize('delete', $questionnaire);
        
        $questionnaire->delete();

        return redirect()->route('questionnaires.index')
            ->with('success', 'Questionnaire deleted successfully.');
    }

    public function showAnswerForm(Questionnaire $questionnaire)
    {
        if (!$questionnaire->is_published || 
            ($questionnaire->expires_at && $questionnaire->expires_at < now())) {
            abort(404, 'Questionnaire not available.');
        }

        return view('questionnaires.answer', compact('questionnaire'));
    }

    public function submitAnswer(Request $request, Questionnaire $questionnaire)
    {
        if (!$questionnaire->is_published || 
            ($questionnaire->expires_at && $questionnaire->expires_at < now())) {
            abort(404, 'Questionnaire not available.');
        }

        // Get required question IDs
        $requiredQuestionIds = $questionnaire->questions()
            ->where('is_required', true)
            ->pluck('id')
            ->toArray();

        // Validate the submission
        $rules = [
            'answers' => 'array'
        ];

        // Add validation rules for required questions
        foreach ($requiredQuestionIds as $questionId) {
            $rules["answers.*.question_id"] = 'required|exists:questions,id';
            $rules["answers.*.selected_options"] = [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($questionId) {
                    $index = explode('.', $attribute)[1];
                    $submittedQuestionId = request()->input("answers.{$index}.question_id");
                    if ($submittedQuestionId == $questionId && empty($value)) {
                        $fail('This question requires an answer.');
                    }
                }
            ];
            $rules["answers.*.selected_options.*"] = 'required|exists:options,id';
        }

        $validated = $request->validate($rules);

        // Delete any existing responses for this user and questionnaire
        $questionnaire->responses()
            ->where('user_id', Auth::id())
            ->delete();

        // Process answers (both required and optional)
        if (!empty($validated['answers'])) {
            foreach ($validated['answers'] as $answer) {
                if (!empty($answer['selected_options'])) {
                    // Create a single response record with all selected options
                    $questionnaire->responses()->create([
                        'user_id' => Auth::id(),
                        'question_id' => $answer['question_id'],
                        'selected_options' => json_encode($answer['selected_options'])
                    ]);
                }
            }
        }

        return redirect()->route('questionnaires.show-response', $questionnaire)
            ->with('success', 'Thank you for completing the questionnaire!');
    }

    public function showResponse(Questionnaire $questionnaire)
    {
        // Get the authenticated user's responses for this questionnaire
        $responses = $questionnaire->responses()
            ->where('user_id', Auth::id())
            ->with(['question', 'option'])
            ->get()
            ->groupBy('question_id')
            ->map(function ($questionResponses) {
                return $questionResponses->first(); // Take only the first response for each question
            });

        if ($responses->isEmpty()) {
            abort(404, 'No responses found for this questionnaire.');
        }

        return view('questionnaires.response', [
            'questionnaire' => $questionnaire,
            'responses' => $responses
        ]);
    }
}
