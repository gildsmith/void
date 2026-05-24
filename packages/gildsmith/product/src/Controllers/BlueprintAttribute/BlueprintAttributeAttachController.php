<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\BlueprintAttribute;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Requests\BlueprintAttribute\BlueprintAttributeAttachRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class BlueprintAttributeAttachController extends Controller
{
    public function __invoke(BlueprintAttributeAttachRequest $request, string $code, string $attribute): Collection
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

        $record = $request->has('required')
            ? ['required' => $request->boolean('required')]
            : [];

        $blueprint->attributes()->syncWithoutDetaching([$attribute->id => $record]);

        return $blueprint->attributes()->get();
    }
}
