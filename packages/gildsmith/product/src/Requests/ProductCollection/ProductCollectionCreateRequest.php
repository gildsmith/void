<?php

declare(strict_types=1);

namespace Gildsmith\Product\Requests\ProductCollection;

use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Support\Requests\Concerns\ValidatesResourceRequest;
use Illuminate\Foundation\Http\FormRequest;

class ProductCollectionCreateRequest extends FormRequest
{
    use ValidatesResourceRequest;

    protected string $validationModel = ProductCollectionInterface::class;

    public function rules(): array
    {
        return $this->resolveValidationModel()->getCreateValidationRules();
    }
}
