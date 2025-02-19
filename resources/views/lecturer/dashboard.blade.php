@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-header">
        <h1>Lecturer Dashboard</h1>
        <div class="role-badge">
            <span class="badge lecturer">Lecturer</span>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="welcome-message">
            <h2>Welcome, {{ auth()->user()->name }}</h2>
            <p>Manage your questionnaires and view student responses here.</p>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="font-size: 1.5rem; font-weight: bold;">My Questionnaires</h2>
                <a href="{{ route('questionnaires.create') }}" class="btn btn-primary">Create New Questionnaire</a>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Responses</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questionnaires as $questionnaire)
                        <tr>
                            <td>{{ $questionnaire->title }}</td>
                            <td>{{ $questionnaire->created_at->format('M d, Y') }}</td>
                            <td>{{ $questionnaire->responses_count }} responses</td>
                            <td>
                                <a href="{{ route('questionnaires.edit', $questionnaire) }}" class="btn">Edit</a>
                                <a href="{{ route('questionnaires.show', $questionnaire) }}" class="btn">View</a>
                                <form action="{{ route('questionnaires.destroy', $questionnaire) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this questionnaire?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                No questionnaires created yet. 
                                <a href="{{ route('questionnaires.create') }}">Create one now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
