<?php

declare(strict_types=1);

namespace Gildsmith\Anvil\Blueprints;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Throwable;

final class BlueprintRenderer
{
    public function run(BlueprintVariables $variables): void
    {
        foreach ($this->filesWithSuffix($variables->packagePath, '.blueprint') as $path) {
            $contents = file_get_contents($path);

            if ($contents === false) {
                throw new InvalidArgumentException('Unable to read blueprint file ['.$path.'].');
            }

            $destination = substr($path, 0, -strlen('.blueprint'));
            $this->writeFile($destination, $this->renderContents($contents, $variables));
            unlink($path);
        }
    }

    private function renderContents(string $contents, BlueprintVariables $variables): string
    {
        return preg_replace_callback('/{{\s*([a-zA-Z][a-zA-Z0-9]*)\s*}}/', function (array $matches) use ($variables): string {
            try {
                return $variables->{$matches[1]};
            } catch (Throwable $exception) {
                throw new InvalidArgumentException('Unknown blueprint variable ['.$matches[1].'].', previous: $exception);
            }
        }, $contents) ?? $contents;
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
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
        );
    }

    private function writeFile(string $path, string $contents): void
    {
        if (file_exists($path)) {
            throw new InvalidArgumentException('Cannot create ['.$path.'] because it already exists.');
        }

        if (file_put_contents($path, $contents) === false) {
            throw new InvalidArgumentException('Unable to write ['.$path.'].');
        }
    }
}
