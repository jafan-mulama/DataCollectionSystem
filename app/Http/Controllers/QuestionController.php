<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Questionnaire $questionnaire)
    {
        $this->authorize('update', $questionnaire);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'is_required' => 'boolean',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $question = $questionnaire->questions()->create([
            'question_text' => $validated['question_text'],
            'is_required' => $validated['is_required'] ?? true,
            'order' => $questionnaire->questions()->count(),
        ]);

        foreach ($validated['options'] as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'order' => $index,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Question added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question->questionnaire);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'is_required' => 'boolean',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'is_required' => $validated['is_required'] ?? true,
        ]);

        // Delete existing options and create new ones
        $question->options()->delete();
        foreach ($validated['options'] as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'order' => $index,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $this->authorize('update', $question->questionnaire);
        
        $question->delete();

        return redirect()->back()
            ->with('success', 'Question deleted successfully.');
    }
}
