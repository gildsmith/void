<?php

namespace Gildsmith\Support\Model\Concerns;

use Gildsmith\Support\Utils\ResolvesInstance;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 *
 * @phpstan-require-extends Model
 */
trait HasAbstractRelationships
{
    use HasRelationships;

    protected function newRelatedInstance($class)
    {
        return tap(resolve($class), function ($instance) {
            if (! $instance->getConnectionName()) {
                $instance->setConnection($this->connection);
            }
        });
    }
}
