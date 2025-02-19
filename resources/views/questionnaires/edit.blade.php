@extends('layouts.app')

@section('content')
<div class="container">
    <div class="questionnaire-form">
        <h2>Edit Questionnaire</h2>

        <form action="{{ route('questionnaires.update', $questionnaire) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $questionnaire->title) }}" 
                       class="form-control" required>
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $questionnaire->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_published" id="is_published" 
                           {{ $questionnaire->is_published ? 'checked' : '' }}>
                    <span>Publish Questionnaire</span>
                </label>
            </div>

            <div class="form-group">
                <label for="expires_at">Expiry Date (Optional)</label>
                <input type="datetime-local" name="expires_at" id="expires_at" 
                       value="{{ old('expires_at', $questionnaire->expires_at ? $questionnaire->expires_at->format('Y-m-d\TH:i') : '') }}"
                       class="form-control">
                @error('expires_at')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('questionnaires.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Questionnaire</button>
            </div>
        </form>

        <!-- Questions Section -->
        <div class="questions-section">
            <div class="section-header">
                <h3>Questions</h3>
                <button onclick="toggleQuestionForm()" class="btn btn-success">Add Question</button>
            </div>

            <!-- Add Question Form -->
            <div id="questionForm" class="question-form" style="display: none;">
                <form action="{{ route('questions.store', $questionnaire) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="question_text">Question</label>
                        <input type="text" name="question_text" id="question_text" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Options</label>
                        <div id="options" class="options-container">
                            <div class="option-input">
                                <input type="text" name="options[]" required class="form-control" placeholder="Option 1">
                            </div>
                            <div class="option-input">
                                <input type="text" name="options[]" required class="form-control" placeholder="Option 2">
                            </div>
                        </div>
                        <button type="button" onclick="addOption()" class="btn btn-secondary btn-sm">Add Option</button>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_required" checked>
                            <span>Required Question</span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Question</button>
                        <button type="button" onclick="toggleQuestionForm()" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Questions List -->
            <div class="questions-list">
                @foreach($questionnaire->questions as $question)
                    <div class="question-card" data-question-id="{{ $question->id }}">
                        <div class="question-header">
                            <h4 class="question-text">{{ $question->question_text }}</h4>
                            <div class="question-actions">
                                <button onclick="editQuestion({{ $question->id }})" class="btn btn-sm btn-secondary">Edit</button>
                                <form action="{{ route('questions.destroy', $question) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class="options-list">
                            @foreach($question->options as $option)
                                <div class="option">{{ $option->option_text }}</div>
                            @endforeach
                            <input type="hidden" class="is-required" value="{{ $question->is_required }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.questionnaire-form {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.questions-section {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.question-form {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.options-container {
    margin-bottom: 1rem;
}

.option-input {
    margin-bottom: 0.5rem;
}

.questions-list {
    margin-top: 2rem;
}

.question-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.question-actions {
    display: flex;
    gap: 0.5rem;
}

.options-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.5rem;
}

.option {
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.delete-form {
    display: inline;
}

.btn-success {
    background-color: #198754;
    color: white;
}

.btn-success:hover {
    background-color: #157347;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

@push('scripts')
<script>
function toggleQuestionForm() {
    const form = document.getElementById('questionForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function addOption() {
    const optionsContainer = document.getElementById('options');
    const optionCount = optionsContainer.children.length + 1;
    
    const optionDiv = document.createElement('div');
    optionDiv.className = 'option-input';
    
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.className = 'form-control';
    input.placeholder = `Option ${optionCount}`;
    input.required = true;
    
    optionDiv.appendChild(input);
    optionsContainer.appendChild(optionDiv);
}

function editQuestion(questionId) {
    // Get the question card element
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    if (!questionCard) return;

    // Get question data
    const questionText = questionCard.querySelector('.question-text').textContent;
    const options = Array.from(questionCard.querySelectorAll('.option')).map(opt => opt.textContent.trim());
    const isRequired = questionCard.querySelector('.is-required').value === '1';

    // Update the question form
    const form = document.getElementById('questionForm');
    const questionInput = form.querySelector('#question_text');
    const optionsContainer = form.querySelector('#options');
    const isRequiredCheckbox = form.querySelector('input[name="is_required"]');
    
    // Update form action and method for editing
    form.action = `/questions/${questionId}`;
    const methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'PUT';
        form.appendChild(input);
    } else {
        methodInput.value = 'PUT';
    }

    // Set question text
    questionInput.value = questionText;

    // Clear and recreate options
    optionsContainer.innerHTML = '';
    options.forEach((optionText, index) => {
        const optionDiv = document.createElement('div');
        optionDiv.className = 'option-input';
        
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'options[]';
        input.className = 'form-control';
        input.placeholder = `Option ${index + 1}`;
        input.value = optionText;
        input.required = true;
        
        optionDiv.appendChild(input);
        optionsContainer.appendChild(optionDiv);
    });

    // Set required checkbox
    isRequiredCheckbox.checked = isRequired;

    // Show the form
    form.style.display = 'block';
    
    // Update button text
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.textContent = 'Update Question';

    // Scroll to the form
    form.scrollIntoView({ behavior: 'smooth' });
}
</script>
@endpush
@endsection
