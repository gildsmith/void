<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\BlueprintFacade as BlueprintFacadeConcrete;
use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Support\Facades\Blueprint as BlueprintFacade;

covers(BlueprintFacadeConcrete::class);

itFulfillsTrashableFacadeContract(
    facade: BlueprintFacade::class,
    model: Blueprint::class,
);
