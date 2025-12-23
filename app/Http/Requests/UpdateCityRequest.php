<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class UpdateCityRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255|min:2',
            'country' => 'sometimes|required|string|max:255|min:2',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $name = $this->input('name');
            $country = $this->input('country');
            $currentId = $this->route('city'); // Get the ID from route parameter

            // Only validate if both name and country are provided
            if (!$name || !$country) {
                return;
            }

            // Load existing cities from storage
            if (Storage::exists('cities.json')) {
                $cities = json_decode(Storage::get('cities.json'), true) ?? [];
                
                // Check if city+country combination already exists (excluding current city)
                foreach ($cities as $id => $city) {
                    if ($id !== $currentId && 
                        strtolower($city['name']) === strtolower($name) && 
                        strtolower($city['country']) === strtolower($country)) {
                        $validator->errors()->add('name', 'This city already exists in this country.');
                        break;
                    }
                }
            }
        });
    }
}
