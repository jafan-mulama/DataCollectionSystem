@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Responses for: {{ $questionnaire->title }}</h2>
                <p class="mt-1 text-gray-600">Total Responses: {{ $responses->count() }}</p>
            </div>
            
            <a href="{{ route('responses.export', $questionnaire) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600">
                Export to CSV
            </a>
        </div>

        @if($responses->isEmpty())
            <p class="text-gray-600">No responses yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            @foreach($questionnaire->questions as $question)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $question->question_text }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Submitted At
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($responses as $userId => $userResponses)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $userResponses->first()->user->name }}
                                </td>
                                @foreach($questionnaire->questions as $question)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $response = $userResponses->first(function($response) use ($question) {
                                                return $response->question_id === $question->id;
                                            });
                                        @endphp
                                        {{ $response ? $response->option->option_text : 'Not answered' }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $userResponses->first()->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
