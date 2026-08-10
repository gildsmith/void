<?php

declare(strict_types=1);

namespace Gildsmith\Support\Providers\Concerns;

use LogicException;
use ReflectionClass;

trait BuildsPackagePaths
{
    /**
     * Build a path from the root of the package containing the provider.
     */
    private function packagePath(string $path): string
    {
        $providerPath = (new ReflectionClass($this))->getFileName();

        if ($providerPath === false) {
            throw new LogicException('Unable to determine package provider path.');
        }

        return dirname($providerPath, 3).DIRECTORY_SEPARATOR.$path;
    }
}
