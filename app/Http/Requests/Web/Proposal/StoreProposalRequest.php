<?php

namespace App\Http\Requests\Web\Proposal;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'submitted_at' => 'nullable|date',
            'is_approved' => 'nullable|boolean',
            'meta_data' => 'nullable|array',
            'meta_data.*' => 'string|nullable',
        ];
    }
}
