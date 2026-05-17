<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Routing;

enum ResourceAbility: string
{
    case ViewAny = 'viewAny';
    case View = 'view';
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
    case ViewTrashed = 'viewTrashed';
    case Trash = 'trash';
    case Restore = 'restore';
}
