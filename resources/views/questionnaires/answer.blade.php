@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">{{ $questionnaire->title }}</h2>
    
    <form method="POST" action="{{ route('questionnaires.submit', $questionnaire) }}" id="questionnaireForm">
        @csrf
        
        @foreach($questionnaire->questions as $question)
            <div class="card" style="margin-bottom: 1rem;">
                <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">
                    {{ $question->question_text }}
                    @if($question->is_required)
                        <span style="color: red;">*</span>
                    @endif
                </h3>
                
                <input type="hidden" name="answers[{{ $loop->index }}][question_id]" value="{{ $question->id }}">
                
                <div class="form-group">
                    @foreach($question->options as $option)
                        <div style="margin-bottom: 0.5rem;">
                            <label style="display: flex; align-items: center;">
                                <input type="checkbox" 
                                       name="answers[{{ $loop->parent->index }}][selected_options][]" 
                                       value="{{ $option->id }}"
                                       class="question-option-{{ $question->id }}"
                                       style="margin-right: 0.5rem;">
                                {{ $option->option_text }}
                            </label>
                        </div>
                    @endforeach
                </div>

                @error("answers.{$loop->index}.selected_options")
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">Submit Answers</button>
            <a href="{{ route('student.dashboard') }}" class="btn" style="margin-left: 0.5rem;">Cancel</a>
        </div>
    </form>
</div>

<style>
.card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    border: 1px solid #ddd;
    background: #f8f9fa;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.btn-primary {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.btn-primary:hover {
    background: #0b5ed7;
}

.alert {
    padding: 0.75rem 1.25rem;
    margin-top: 0.5rem;
    border-radius: 4px;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
</style>

<script>
document.getElementById('questionnaireForm').addEventListener('submit', function(e) {
    const questions = @json($questionnaire->questions);
    
    for (const question of questions) {
        if (question.is_required) {
            const checkboxes = document.getElementsByClassName(`question-option-${question.id}`);
            let checked = false;
            
            for (const checkbox of checkboxes) {
                if (checkbox.checked) {
                    checked = true;
                    break;
                }
            }
            
            if (!checked) {
                e.preventDefault();
                alert(`Please answer the required question: ${question.question_text}`);
                return;
            }
        }
    }
});
</script>
@endsection
