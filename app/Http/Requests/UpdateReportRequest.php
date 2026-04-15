<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    private const DEFAULT_ACTIVITY = 'N/A';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activity' => $this->normalizeTextArray(
                $this->input('activity', []),
                self::DEFAULT_ACTIVITY
            ),
            'details' => $this->normalizeTextArray($this->input('details', [])),
            'remarks' => $this->normalizeTextArray($this->input('remarks', [])),
        ]);
    }

    public function rules(): array
    {
        return [
            'entry_id' => ['nullable', 'array'],
            'entry_id.*' => ['nullable', 'integer', 'exists:report_entries,id'],
            'start_date' => ['required', 'array'],
            'start_date.*' => ['required', 'date'],
            'end_date' => ['nullable', 'array'],
            'end_date.*' => ['nullable', 'date'],
            'activity' => ['nullable', 'array'],
            'activity.*' => ['nullable', 'string'],
            'details' => ['nullable', 'array'],
            'details.*' => ['nullable', 'string'],
            'remarks' => ['nullable', 'array'],
            'remarks.*' => ['nullable', 'string'],
        ];
    }

    private function normalizeTextArray(mixed $values, ?string $default = null): array
    {
        if (!is_array($values)) {
            return [];
        }

        return array_map(function (mixed $value) use ($default) {
            $value = is_string($value) ? trim($value) : $value;

            if ($value === null || $value === '') {
                return $default;
            }

            return $value;
        }, $values);
    }
}
