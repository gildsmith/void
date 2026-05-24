<?php

declare(strict_types=1);

namespace Gildsmith\Product\Requests\Product;

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Support\Requests\Concerns\ValidatesResourceRequest;
use Illuminate\Foundation\Http\FormRequest;

class ProductFindRequest extends FormRequest
{
    use ValidatesResourceRequest;

    protected string $validationModel = ProductInterface::class;

    public function rules(): array
    {
        return [];
    }
}
