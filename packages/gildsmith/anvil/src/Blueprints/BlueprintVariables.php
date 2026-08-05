<?php

declare(strict_types=1);

namespace Gildsmith\Anvil\Blueprints;

final class BlueprintVariables
{
    public function __construct(
        public readonly string $vendorName,
        public readonly string $packageName,
        public readonly string $authorName,
    ) {}

    public string $composerName {
        get => $this->vendorName.'/'.$this->packageName;
    }

    public string $packagePath {
        get => dirname(__DIR__, 4)
            .DIRECTORY_SEPARATOR.$this->vendorName
            .DIRECTORY_SEPARATOR.$this->packageName;
    }

    /**
     * @var array<int, string>
     */
    public array $blueprintPackagePaths {
        get {
            $path = dirname(__DIR__, 2)
                .DIRECTORY_SEPARATOR.'blueprints'
                .DIRECTORY_SEPARATOR.'packages';

            return array_map(
                fn (string $name): string => $path.DIRECTORY_SEPARATOR.$name,
                ['scaffolding', 'github', 'pint', 'tests'],
            );
        }
    }

    public string $vendorNamespace {
        get => $this->namespaceSegment($this->vendorName);
    }

    public string $packageNamespace {
        get => $this->namespaceSegment($this->packageName);
    }

    public string $namespace {
        get => $this->vendorNamespace.'\\'.$this->packageNamespace;
    }

    public string $namespaceJson {
        get => $this->jsonNamespace($this->namespace);
    }

    public string $providerNamespace {
        get => $this->namespace.'\\Providers';
    }

    public string $providerNamespaceJson {
        get => $this->jsonNamespace($this->providerNamespace);
    }

    public string $providerClass {
        get => 'AppServiceProvider';
    }

    public string $packageTitle {
        get => $this->title($this->packageName);
    }

    private function jsonNamespace(string $value): string
    {
        return str_replace('\\', '\\\\', $value);
    }

    private function namespaceSegment(string $value): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_', '.'], ' ', $value)));
    }

    private function title(string $value): string
    {
        return ucwords(str_replace(['-', '_', '.'], ' ', $value));
    }
}
