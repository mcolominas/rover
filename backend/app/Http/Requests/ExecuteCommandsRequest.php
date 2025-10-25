<?php

namespace App\Http\Requests;

class ExecuteCommandsRequest extends APIFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('commands')) {
            $this->merge([
                'commands' => strtoupper($this->input('commands')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'commands' => ['required', 'string', 'regex:/^[FLR]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'commands.regex' => 'Commands may only contain F, L or R characters.',
        ];
    }
}
