<?php

namespace App\Http\Requests;

class LaunchRoverRequest extends APIFormRequest
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
