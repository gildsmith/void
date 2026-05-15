<?php

declare(strict_types=1);

namespace Gildsmith\Support\Model\Concerns;

use Gildsmith\Support\Exceptions\ImmutableAttributeException;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 *
 * @phpstan-require-extends Model
 */
trait HasImmutableAttributes
{
    public static function bootHasImmutableAttributes(): void
    {
        static::updating(function (self $model): void {
            foreach ($model->immutableAttributes() as $attribute) {
                if ($model->isDirty($attribute)) {
                    throw new ImmutableAttributeException($model, $attribute);
                }
            }
        });
    }

    /**
     * @return list<string>
     */
    public function immutableAttributes(): array
    {
        return property_exists($this, 'immutable')
            ? $this->immutable
            : [];
    }
}
