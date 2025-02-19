<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="dashboard-header">
                        <h1>Welcome, {{ auth()->user()->name }}</h1>
                        <div class="role-badge">
                            @if(auth()->user()->isAdmin())
                                <span class="badge admin">Administrator</span>
                            @elseif(auth()->user()->isLecturer())
                                <span class="badge lecturer">Lecturer</span>
                            @elseif(auth()->user()->isStudent())
                                <span class="badge student">Student</span>
                            @endif
                        </div>
                    </div>

                    <div class="dashboard-content">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
.dashboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.role-badge {
    padding: 0.5rem 1rem;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-weight: bold;
    color: white;
}

.admin {
    background-color: #dc3545;
}

.lecturer {
    background-color: #0d6efd;
}

.student {
    background-color: #198754;
}
</style>
