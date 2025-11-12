<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:35'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sold_out' => ['boolean'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.max' => 'Il titolo non può superare i 35 caratteri.',
            'price.required' => 'Il prezzo è obbligatorio.',
            'price.numeric' => 'Il prezzo deve essere un numero valido.',
            'price.min' => 'Il prezzo non può essere negativo.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Prevent editors from changing sold_out status
            if ($this->user() && $this->user()->hasRole('editor')) {
                if ($this->has('sold_out')) {
                    $validator->errors()->add(
                        'sold_out',
                        'Gli editor non possono modificare lo stato di venduto.'
                    );
                }
            }
        });
    }
}

