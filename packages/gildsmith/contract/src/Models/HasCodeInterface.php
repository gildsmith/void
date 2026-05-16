<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Models;

interface HasCodeInterface
{
    public function getCode(): string;
}
