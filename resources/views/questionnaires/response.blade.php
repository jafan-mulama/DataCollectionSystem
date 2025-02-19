@extends('layouts.app')

@section('content')
<div class="card">
    <div class="header">
        <h2 class="title">{{ $questionnaire->title }}</h2>
        @if($questionnaire->description)
            <p class="description">{{ $questionnaire->description }}</p>
        @endif
    </div>

    <div class="responses">
        @foreach($questionnaire->questions as $question)
            <div class="question-card">
                <h3 class="question-text">{{ $question->question_text }}</h3>
                
                <div class="selected-options">
                    <h4>Your Answer:</h4>
                    @if(isset($responses[$question->id]))
                        <ul>
                            @foreach($responses[$question->id]->selected_options as $option)
                                <li>{{ $option->option_text }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="no-answer">No answer provided</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="actions">
        <a href="{{ route('questionnaires.index') }}" class="btn">Back to Questionnaires</a>
    </div>
</div>

<style>
.card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 2rem auto;
}

.header {
    margin-bottom: 2rem;
}

.title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 0.5rem;
}

.description {
    color: #666;
    margin-bottom: 1rem;
}

.question-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.question-text {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 1rem;
}

.selected-options {
    margin-left: 1rem;
}

.selected-options h4 {
    font-size: 1rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.selected-options ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.selected-options li {
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    margin-bottom: 0.5rem;
    border: 1px solid #ddd;
}

.no-answer {
    color: #999;
    font-style: italic;
}

.actions {
    margin-top: 2rem;
    text-align: center;
}

.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.btn:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
}
</style>
@endsection
