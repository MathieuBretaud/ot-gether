<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class EventRequest extends FormRequest
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
            'event_category_id' => 'required|integer',
            'event_tag_id' => 'required|integer',
            'creator_id' => 'required|integer',
            'title' => 'required|string',
            'slug' => 'required|string',
            'picture' => 'string',
            'description' => 'required|string',
            'address' => 'string',
            'city' => 'string',
            'region' => 'string',
            'is_IRL' => 'required|boolean',
            'participant_min' => 'required|integer',
            'participant_max' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'event_category_id.required|integer|exists:event_categories,id' => 'Une catégorie est nécessaire',
            'event_tag_id.required|integer|exists:event_tags,id' => 'Ce email est déjà pris',
            'creator_id.required|integer|exists:users,id' => 'Un mot de passe est requis',
            'slug.required|string' => 'Une ville est requise',
            'picture.required|string' => 'Un Pseudo est requis',
            'description.required|string' => 'Ce pseudo est déjà pris',
            'address.required|string' => 'Un Prénom est requis',
            'city.required|string' => 'Un Nom est requis',
            'region.required|string' => 'Un Nom est requis',
            'is_IRL.required|boolean' => 'Un Nom est requis',
            'participant_min.required|integer' => 'Un Nom est requis',
            'participant_max.required|integer' => 'Un Nom est requis',
            'start_date.required|date' => 'Un Nom est requis',
            'end_date.required|date' => 'Un Nom est requis',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'creator_id' => auth()->user()->id,
            'event_tag_id' => 1,
            'picture' => 'https://picsum.photos/200/200',
        ]);
    }
}
