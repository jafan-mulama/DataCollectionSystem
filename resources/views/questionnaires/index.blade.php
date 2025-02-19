@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                @if(auth()->user()->isStudent())
                    Available Questionnaires
                @else
                    My Questionnaires
                @endif
            </h2>
            @if(auth()->user()->isLecturer() || auth()->user()->isAdmin())
                <a href="{{ route('questionnaires.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Questionnaire
                </a>
            @endif
        </div>

        @if($questionnaires->isEmpty())
            <p class="text-gray-600">No questionnaires available.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($questionnaires as $questionnaire)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $questionnaire->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ $questionnaire->description }}</p>
                            
                            <div class="flex items-center justify-between mt-4">
                                <div class="text-sm text-gray-600">
                                    @if($questionnaire->is_published)
                                        <span class="text-green-600">Published</span>
                                    @else
                                        <span class="text-yellow-600">Draft</span>
                                    @endif
                                </div>
                                
                                <div class="flex space-x-2">
                                    @if(auth()->user()->isStudent())
                                        <a href="{{ route('questionnaires.show', $questionnaire) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                                            Fill Out
                                        </a>
                                    @else
                                        <a href="{{ route('questionnaires.edit', $questionnaire) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                                            Edit
                                        </a>
                                        
                                        @if($questionnaire->responses()->exists())
                                            <a href="{{ route('responses.index', $questionnaire) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600">
                                                View Responses
                                            </a>
                                        @endif
                                        
                                        <form action="{{ route('questionnaires.destroy', $questionnaire) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this questionnaire?')"
                                                    class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
