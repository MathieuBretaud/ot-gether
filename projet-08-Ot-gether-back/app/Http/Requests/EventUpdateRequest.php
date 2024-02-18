<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
            'event_category_id' => 'integer',
            'event_tag_id' => 'integer|exists:event_tags,id',
            'creator_id' => 'integer|exists:users,id',
            'slug' => 'string',
            'title' => 'string',
            'picture' => 'string',
            'description' => 'string',
            'address' => 'string|nullable',
            'city' => 'string|nullable',
            'region' => 'string|nullable',
            'is_IRL' => 'boolean',
            'participant_min' => 'integer',
            'participant_max' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
