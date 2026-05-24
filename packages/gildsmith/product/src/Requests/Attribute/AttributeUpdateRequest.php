<?php

declare(strict_types=1);

namespace Gildsmith\Product\Requests\Attribute;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Support\Requests\Concerns\ValidatesResourceRequest;
use Illuminate\Foundation\Http\FormRequest;

class AttributeUpdateRequest extends FormRequest
{
    use ValidatesResourceRequest;

    protected string $validationModel = AttributeInterface::class;

    public function rules(): array
    {
        return $this->resolveValidationModel()->getUpdateValidationRules();
    }
}
