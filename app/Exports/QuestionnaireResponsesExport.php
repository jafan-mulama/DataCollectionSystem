<?php

namespace App\Exports;

use App\Models\Questionnaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class QuestionnaireResponsesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $questionnaire;

    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function collection()
    {
        return $this->questionnaire->responses()
            ->with(['user', 'question', 'option'])
            ->get()
            ->groupBy('user_id')
            ->map(function ($userResponses) {
                $firstResponse = $userResponses->first();
                $data = [
                    'user_id' => $firstResponse->user_id,
                    'user_name' => $firstResponse->user->name,
                    'submitted_at' => $firstResponse->created_at,
                ];

                // Add responses for each question
                foreach ($this->questionnaire->questions as $question) {
                    $responses = $userResponses
                        ->where('question_id', $question->id)
                        ->pluck('option.option_text')
                        ->join(', ');
                    
                    $data["q{$question->id}"] = $responses;
                }

                return $data;
            })
            ->values();
    }

    public function headings(): array
    {
        $headers = [
            'User ID',
            'User Name',
            'Submitted At'
        ];

        // Add question headers
        foreach ($this->questionnaire->questions as $question) {
            $headers[] = $question->question_text;
        }

        return $headers;
    }

    public function map($row): array
    {
        $data = [
            $row['user_id'],
            $row['user_name'],
            $row['submitted_at']->format('Y-m-d H:i:s')
        ];

        // Add responses
        foreach ($this->questionnaire->questions as $question) {
            $data[] = $row["q{$question->id}"] ?? 'No response';
        }

        return $data;
    }
}
