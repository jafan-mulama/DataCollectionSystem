@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Create New Questionnaire</h2>

    <form method="POST" action="{{ route('questionnaires.store') }}" id="questionnaireForm">
        @csrf
        
        <div class="form-group">
            <label for="title">Questionnaire Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div id="questions">
            <h3 style="font-size: 1.2rem; margin: 1rem 0;">Questions</h3>
            
            <div class="card question-card" data-question="0">
                <div class="form-group">
                    <label>Question Text</label>
                    <input type="text" class="form-control" name="questions[0][text]" required>
                </div>

                <div class="options">
                    <h4 style="font-size: 1rem; margin: 1rem 0;">Options</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" name="questions[0][options][]" placeholder="Option 1" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="questions[0][options][]" placeholder="Option 2" required>
                    </div>
                </div>

                <button type="button" class="btn" onclick="addOption(this)" style="margin-right: 0.5rem;">Add Option</button>
                <button type="button" class="btn btn-danger" onclick="removeQuestion(this)">Remove Question</button>
            </div>
        </div>

        <button type="button" class="btn" onclick="addQuestion()" style="margin: 1rem 0;">Add Question</button>

        <div style="margin-top: 1rem;">
            <div class="form-group" style="margin-bottom: 1rem;">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1">
                    <label class="form-check-label" for="is_published">Publish Immediately</label>
                    <small class="form-text text-muted">If unchecked, questionnaire will be saved as draft.</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Questionnaire</button>
            <a href="{{ route('questionnaires.index') }}" class="btn" style="margin-left: 0.5rem;">Cancel</a>
        </div>
    </form>
</div>

<script>
    let questionCount = 1;

    function addQuestion() {
        const questionsDiv = document.getElementById('questions');
        const newQuestion = document.createElement('div');
        newQuestion.className = 'card question-card';
        newQuestion.dataset.question = questionCount;
        newQuestion.style.marginTop = '1rem';
        
        newQuestion.innerHTML = `
            <div class="form-group">
                <label>Question Text</label>
                <input type="text" class="form-control" name="questions[${questionCount}][text]" required>
            </div>

            <div class="options">
                <h4 style="font-size: 1rem; margin: 1rem 0;">Options</h4>
                <div class="form-group">
                    <input type="text" class="form-control" name="questions[${questionCount}][options][]" placeholder="Option 1" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="questions[${questionCount}][options][]" placeholder="Option 2" required>
                </div>
            </div>

            <button type="button" class="btn" onclick="addOption(this)" style="margin-right: 0.5rem;">Add Option</button>
            <button type="button" class="btn btn-danger" onclick="removeQuestion(this)">Remove Question</button>
        `;

        questionsDiv.appendChild(newQuestion);
        questionCount++;
    }

    function addOption(button) {
        const optionsDiv = button.previousElementSibling;
        const questionDiv = button.closest('.question-card');
        const questionIndex = questionDiv.dataset.question;
        const optionCount = optionsDiv.children.length;

        const newOption = document.createElement('div');
        newOption.className = 'form-group';
        newOption.innerHTML = `
            <div style="display: flex; gap: 0.5rem;">
                <input type="text" class="form-control" name="questions[${questionIndex}][options][]" 
                       placeholder="Option ${optionCount + 1}" required>
                <button type="button" class="btn btn-danger" onclick="removeOption(this)">Remove</button>
            </div>
        `;

        optionsDiv.appendChild(newOption);
    }

    function removeQuestion(button) {
        const questionCard = button.closest('.question-card');
        if (document.querySelectorAll('.question-card').length > 1) {
            questionCard.remove();
        } else {
            alert('You must have at least one question.');
        }
    }

    function removeOption(button) {
        const optionDiv = button.closest('.form-group');
        const optionsDiv = optionDiv.parentElement;
        if (optionsDiv.children.length > 2) {
            optionDiv.remove();
        } else {
            alert('Each question must have at least two options.');
        }
    }
</script>
@endsection
