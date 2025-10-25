<?php

namespace App\Http\Requests;

class StorePlanetRequest extends APIFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
