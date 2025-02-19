@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <div class="role-badge">
            <span class="badge admin">Administrator</span>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="welcome-message">
            <h2>Welcome, {{ auth()->user()->name }}</h2>
            <p>Manage users and view system statistics here.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-breakdown">
                    <div>Admins: {{ $usersByRole['admin'] }}</div>
                    <div>Lecturers: {{ $usersByRole['lecturer'] }}</div>
                    <div>Students: {{ $usersByRole['student'] }}</div>
                </div>
            </div>

            <div class="stat-card">
                <h3>Total Questionnaires</h3>
                <div class="stat-number">{{ $totalQuestionnaires }}</div>
            </div>

            <div class="stat-card">
                <h3>Total Responses</h3>
                <div class="stat-number">{{ $totalResponses }}</div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('admin.users') }}" class="btn btn-primary">Manage Users</a>
            <a href="{{ route('admin.statistics') }}" class="btn btn-secondary">View Statistics</a>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-card h3 {
    color: #666;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 1rem;
}

.stat-breakdown {
    color: #666;
    font-size: 0.9rem;
}

.stat-breakdown div {
    margin: 0.25rem 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.action-buttons .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}
</style>
@endsection
