<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\BlueprintAttribute;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Requests\BlueprintAttribute\BlueprintAttributeIndexRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class BlueprintAttributeIndexController extends Controller
{
    public function __invoke(BlueprintAttributeIndexRequest $request, string $code): Collection
    {
        /** @var (Model&BlueprintInterface)|null $blueprint */
        $blueprint = Blueprint::find($code);

        if ($blueprint === null) {
            abort(404);
        }

        return $blueprint->attributes()->get();
    }
}
