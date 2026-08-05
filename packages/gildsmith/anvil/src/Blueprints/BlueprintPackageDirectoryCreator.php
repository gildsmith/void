<?php

declare(strict_types=1);

namespace Gildsmith\Anvil\Blueprints;

use FilesystemIterator;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

final class BlueprintPackageDirectoryCreator
{
    private ?string $createdPackagePath = null;

    private ?string $createdVendorPath = null;

    public function run(BlueprintVariables $variables): void
    {
        if (file_exists($variables->packagePath)) {
            throw new InvalidArgumentException("Package [$variables->composerName] already exists at [$variables->packagePath].");
        }

        $vendorPath = dirname($variables->packagePath);

        if (! is_dir($vendorPath)) {
            if (! mkdir($vendorPath, 0775, true)) {
                throw new RuntimeException("Unable to create vendor directory [$vendorPath].");
            }

            $this->createdVendorPath = $vendorPath;
        }

        if (! mkdir($variables->packagePath, 0775)) {
            throw new RuntimeException("Unable to create package directory [$variables->packagePath].");
        }

        $this->createdPackagePath = $variables->packagePath;
    }

    public function rollback(): void
    {
        if ($this->createdPackagePath !== null && is_dir($this->createdPackagePath)) {
            $items = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->createdPackagePath, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST,
            );

            foreach ($items as $item) {
                $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
            }

            rmdir($this->createdPackagePath);
        }

        if ($this->createdVendorPath !== null && is_dir($this->createdVendorPath)) {
            rmdir($this->createdVendorPath);
        }
    }
}
