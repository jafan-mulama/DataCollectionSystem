@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ $questionnaire->title }}</h2>
            @if($questionnaire->description)
                <p class="mt-2 text-gray-600">{{ $questionnaire->description }}</p>
            @endif
        </div>

        <form action="{{ route('responses.store', $questionnaire) }}" method="POST" class="space-y-8">
            @csrf

            @foreach($questionnaire->questions as $question)
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-900">
                            {{ $question->question_text }}
                            @if($question->is_required)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                    </div>

                    <div class="space-y-2">
                        @foreach($question->options as $option)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="responses[{{ $question->id }}]" 
                                       value="{{ $option->id }}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       {{ $question->is_required ? 'required' : '' }}>
                                <label class="ml-3 block text-sm font-medium text-gray-700">
                                    {{ $option->option_text }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @error('responses.' . $question->id)
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex justify-end space-x-3">
                @if(Auth::user()->isLecturer() || Auth::user()->isAdmin())
                    <a href="{{ route('questionnaires.analysis', $questionnaire) }}" 
                       class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">
                        View Analysis
                    </a>
                @endif
                <a href="{{ route('questionnaires.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                    Submit Responses
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
