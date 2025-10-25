<?php

namespace App\Http\Requests;

use App\Exceptions\RoverAlreadyExistsException;
use App\Models\Planet;

class LaunchRoverRequest extends APIFormRequest
{
    protected Planet|null $planet = null;

    public function authorize(): bool
    {
        return true;
    }

    public function getPlanet(): Planet|null
    {
        return $this->planet;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('direction')) {
            $this->merge([
                'direction' => mb_strtoupper($this->input('direction')),
            ]);
        }

        if ($this->has('planet_id')) {
            $this->planet = Planet::with('rover')->find($this->input('planet_id'));
        }
    }

    public function rules(): array
    {
        $maxX = null;
        $maxY = null;

        if ($this->planet instanceof Planet) {
            $maxX = $this->planet->width - 1;
            $maxY = $this->planet->height - 1;
        }

        return [
            'planet_id' => 'required|integer|exists:planets,id',
            'x' => [
                'required',
                'integer',
                'min:0',
                $maxX !== null ? "max:{$maxX}" : '',
            ],
            'y' => [
                'required',
                'integer',
                'min:0',
                $maxY !== null ? "max:{$maxY}" : '',
            ],
            'direction' => 'required|string|in:N,E,S,W',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->planet instanceof Planet && $this->planet->rover) {
                throw new RoverAlreadyExistsException();
            }
        });
    }
}
