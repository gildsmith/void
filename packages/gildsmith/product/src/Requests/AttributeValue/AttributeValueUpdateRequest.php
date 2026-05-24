<?php

declare(strict_types=1);

namespace Gildsmith\Product\Requests\AttributeValue;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Support\Requests\Concerns\ValidatesResourceRequest;
use Illuminate\Foundation\Http\FormRequest;

class AttributeValueUpdateRequest extends FormRequest
{
    use ValidatesResourceRequest;

    protected string $validationModel = AttributeValueInterface::class;

    public function rules(): array
    {
        return $this->resolveValidationModel()->getUpdateValidationRules();
    }
}
