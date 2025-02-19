<?php

namespace App\Exports;

use App\Models\Questionnaire;
use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class ResponsesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $questionnaire;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function collection()
    {
        return Response::where('questionnaire_id', $this->questionnaire->id)
            ->with(['user', 'question'])
            ->get()
            ->groupBy(['user_id', 'question_id'])
            ->map(function ($userResponses) {
                return $userResponses->map(function ($questionResponses) {
                    return $questionResponses->first();
                });
            })
            ->flatten(1);
    }

    public function headings(): array
    {
        return [
            'Respondent',
            'Question',
            'Selected Options',
            'Submission Date'
        ];
    }

    public function map($response): array
    {
        $selectedOptions = collect(json_decode($response->selected_options, true))
            ->map(function ($optionId) {
                return \App\Models\Option::find($optionId)->option_text;
            })
            ->implode(', ');

        return [
            $response->user->name,
            $response->question->question_text,
            $selectedOptions,
            $response->created_at->format('Y-m-d H:i:s')
        ];
    }
}
