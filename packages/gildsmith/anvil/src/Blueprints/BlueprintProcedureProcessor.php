<?php

declare(strict_types=1);

namespace Gildsmith\Anvil\Blueprints;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class BlueprintProcedureProcessor
{
    public function run(BlueprintVariables $variables): void
    {
        foreach ($this->filesWithSuffix($variables->packagePath, '.procedure') as $path) {
            // TODO: execute scoped procedure files once the procedure API settles.
            unlink($path);
        }
    }

    /**
     * @return array<int, string>
     */
    private function filesWithSuffix(string $path, string $suffix): array
    {
        $files = [];

        foreach ($this->iterator($path) as $item) {
            if (! $item instanceof SplFileInfo || ! $item->isFile()) {
                continue;
            }

            if (str_ends_with($item->getPathname(), $suffix)) {
                $files[] = $item->getPathname();
            }
        }

        sort($files);

        return $files;
    }

    private function iterator(string $path): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
        );
    }
}
