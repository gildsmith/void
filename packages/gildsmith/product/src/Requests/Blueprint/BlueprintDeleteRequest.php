<?php

declare(strict_types=1);

namespace Gildsmith\Product\Requests\Blueprint;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Support\Requests\Concerns\ValidatesResourceRequest;
use Illuminate\Foundation\Http\FormRequest;

class BlueprintDeleteRequest extends FormRequest
{
    use ValidatesResourceRequest;

    protected string $validationModel = BlueprintInterface::class;

    public function rules(): array
    {
        return [];
    }
}
