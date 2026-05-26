<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Requests\Auth;

use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Requests\Concerns\ValidatesResourceRequest;
use Illuminate\Foundation\Http\FormRequest;
use LogicException;

class RegisterRequest extends FormRequest
{
    use ValidatesResourceRequest;

    protected string $validationModel = UserInterface::class;

    /**
     * @return array<string, array<int, mixed>>
     *
     * @throws LogicException
     */
    public function rules(): array
    {
        $rules = $this->resolveValidationModel()->getCreateValidationRules();

        $rules['email'][] = 'unique:users,email';

        return $rules;
    }
}
