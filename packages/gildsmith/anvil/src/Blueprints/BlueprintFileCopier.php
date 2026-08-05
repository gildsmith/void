<?php

declare(strict_types=1);

namespace Gildsmith\Anvil\Blueprints;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class BlueprintFileCopier
{
    public function run(BlueprintVariables $variables): void
    {
        foreach ($variables->blueprintPackagePaths as $sourcePath) {
            $this->copyPackage($sourcePath, $variables->packagePath);
        }
    }

    private function copyPackage(string $sourcePath, string $targetPath): void
    {
        if (! is_dir($sourcePath)) {
            throw new InvalidArgumentException('Blueprint package ['.$sourcePath.'] does not exist.');
        }

        foreach ($this->iterator($sourcePath) as $item) {
            if (! $item instanceof SplFileInfo) {
                continue;
            }

            $relativePath = $this->relativePath($sourcePath, $item->getPathname());
            $destination = rtrim($targetPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$relativePath;

            if ($item->isDir()) {
                $this->ensureDirectory($destination);

                continue;
            }

            $contents = file_get_contents($item->getPathname());

            if ($contents === false) {
                throw new InvalidArgumentException('Unable to read blueprint file ['.$item->getPathname().'].');
            }

            $this->writeFile($destination, $contents);
        }
    }

    private function ensureDirectory(string $path): void
    {
        if (! is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    private function iterator(string $path): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
        );
    }

    private function relativePath(string $sourcePath, string $path): string
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, substr($path, strlen($sourcePath) + 1));
    }

    private function writeFile(string $path, string $contents): void
    {
        if (file_exists($path)) {
            throw new InvalidArgumentException('Cannot create ['.$path.'] because it already exists.');
        }

        $this->ensureDirectory(dirname($path));

        if (file_put_contents($path, $contents) === false) {
            throw new InvalidArgumentException('Unable to write ['.$path.'].');
        }
    }
}
