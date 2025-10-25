<?php

namespace App\Http\Requests;

class ExecuteCommandsRequest extends APIFormRequest
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
