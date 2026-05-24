<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\BlueprintAttribute;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Requests\BlueprintAttribute\BlueprintAttributeUpdateRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class BlueprintAttributeUpdateController extends Controller
{
    public function __invoke(BlueprintAttributeUpdateRequest $request, string $code, string $attribute): Collection
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

        if (! $request->has('required')) {
            abort(422, 'The required field must be present.');
        }

        $updated = $blueprint->attributes()->updateExistingPivot($attribute->id, [
            'required' => $request->boolean('required'),
        ]);

        if ($updated === 0) {
            abort(404);
        }

        return $blueprint->attributes()->get();
    }
}
