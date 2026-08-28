<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}
