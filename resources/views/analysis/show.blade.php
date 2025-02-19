@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $questionnaire->title }} - Analysis</h1>
            <div>
                <a href="{{ route('questionnaires.export', $questionnaire) }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Export to CSV
                </a>
            </div>
        </div>

        @foreach($analysis as $questionId => $data)
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ $data['question'] }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-4 shadow">
                        <canvas id="chart_{{ $questionId }}" class="w-full"></canvas>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow">
                        <h3 class="text-lg font-medium text-gray-600 mb-3">Response Distribution</h3>
                        <div class="space-y-3">
                            @foreach($data['options'] as $optionId => $option)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">{{ $option['text'] }}</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-500">
                                            {{ $option['count'] }} responses
                                        </span>
                                        <span class="text-sm font-medium text-blue-500">
                                            ({{ $option['percentage'] }}%)
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $option['percentage'] }}%"></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-gray-600">
                                Total Responses: <span class="font-medium">{{ $data['total_responses'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($analysis as $questionId => $data)
        new Chart(document.getElementById('chart_{{ $questionId }}').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! json_encode(array_column($data['options'], 'text')) !!},
                datasets: [{
                    data: {!! json_encode(array_column($data['options'], 'count')) !!},
                    backgroundColor: [
                        '#4F46E5', '#10B981', '#F59E0B', '#EF4444',
                        '#6366F1', '#8B5CF6', '#EC4899', '#14B8A6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Response Distribution'
                    }
                }
            }
        });
    @endforeach
});
</script>
@endpush
@endsection
