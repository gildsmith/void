<?php

declare(strict_types=1);

namespace Gildsmith\Support\Model\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-require-extends Model
 */
trait HasCode
{
    public string $code {
        get => (string) $this->getAttribute('code');
    }
}
