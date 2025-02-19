@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-header">
        <h1>Student Dashboard</h1>
        <div class="role-badge">
            <span class="badge student">Student</span>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="welcome-message">
            <h2>Welcome, {{ auth()->user()->name }}</h2>
            <p>View and answer questionnaires assigned to you here.</p>
        </div>

        <div class="questionnaires-grid">
            @forelse($questionnaires as $questionnaire)
                <div class="questionnaire-card">
                    <div class="card-header">
                        <h3>{{ $questionnaire->title }}</h3>
                        <span class="lecturer-name">By: {{ $questionnaire->user->name }}</span>
                    </div>
                    
                    <div class="card-body">
                        @if($questionnaire->description)
                            <p class="description">{{ $questionnaire->description }}</p>
                        @endif

                        @if($questionnaire->userHasResponded(auth()->user()))
                            <div class="status-badge completed">
                                <i class="fas fa-check-circle"></i> Completed
                            </div>
                        @else
                            <div class="status-badge pending">
                                <i class="fas fa-clock"></i> Not Completed
                            </div>
                        @endif

                        @if($questionnaire->expires_at)
                            <div class="expiry-info">
                                Expires: {{ $questionnaire->expires_at->format('M d, Y H:i') }}
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        @if(!$questionnaire->userHasResponded(auth()->user()))
                            <a href="{{ route('questionnaires.answer', $questionnaire) }}" class="btn btn-primary">
                                Start Questionnaire
                            </a>
                        @else
                            <a href="{{ route('questionnaires.show-response', $questionnaire) }}" class="btn btn-secondary">
                                View Response
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>No questionnaires available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.questionnaires-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.questionnaire-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s;
}

.questionnaire-card:hover {
    transform: translateY(-2px);
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.card-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.lecturer-name {
    font-size: 0.875rem;
    color: #666;
}

.card-body {
    padding: 1.5rem;
}

.description {
    color: #555;
    margin-bottom: 1rem;
    font-size: 0.9375rem;
    line-height: 1.5;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.status-badge i {
    margin-right: 0.5rem;
}

.status-badge.completed {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.pending {
    background: #fee2e2;
    color: #991b1b;
}

.expiry-info {
    font-size: 0.875rem;
    color: #666;
    margin-top: 1rem;
}

.card-footer {
    padding: 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.empty-state i {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #64748b;
    font-size: 1.125rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}
</style>
@endsection
