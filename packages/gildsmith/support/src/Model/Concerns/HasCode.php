<?php

declare(strict_types=1);

namespace Gildsmith\Support\Model\Concerns;

trait HasCode
{
    public function getCode(): string
    {
        return (string) $this->getAttribute('code');
    }
}
