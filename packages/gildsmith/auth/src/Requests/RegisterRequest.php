<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Requests;

use Gildsmith\Contract\User\UserInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        /** @var Model $user */
        $user = resolve(UserInterface::class);

        return [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($user->getTable(), 'email')],
            'password' => ['required', 'string', 'min:8'],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}
