<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductCollectionUpdateController extends Controller
{
    public function __invoke(Request $request, string $code): ?ProductCollectionInterface
    {
        return ProductCollection::update($code, $request->all());
    }
}
