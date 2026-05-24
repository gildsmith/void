<?php

declare(strict_types=1);

namespace Gildsmith\Product\Requests\BlueprintAttribute;

use Gildsmith\Support\Requests\Concerns\ValidatesResourceRequest;
use Illuminate\Foundation\Http\FormRequest;

class BlueprintAttributeDetachRequest extends FormRequest
{
    use ValidatesResourceRequest;

    public function rules(): array
    {
        return [];
    }
}
