<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\BlueprintAttribute;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Requests\BlueprintAttribute\BlueprintAttributeDetachRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class BlueprintAttributeDetachController extends Controller
{
    public function __invoke(BlueprintAttributeDetachRequest $request, string $code, string $attribute): bool
    {
        /** @var (Model&BlueprintInterface)|null $blueprint */
        $blueprint = Blueprint::find($code);

        if ($blueprint === null) {
            abort(404);
        }

        $attribute = Attribute::query()
            ->where('code', $attribute)
            ->first();

        if ($attribute === null) {
            abort(404);
        }

        $blueprint->attributes()->detach($attribute->id);

        return true;
    }
}
