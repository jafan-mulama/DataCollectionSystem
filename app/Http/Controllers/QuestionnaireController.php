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
        ]);

        $questionnaire = Auth::user()->questionnaires()->create($validated);

        return redirect()->route('questionnaires.edit', $questionnaire)
            ->with('success', 'Questionnaire created successfully.');
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
}
