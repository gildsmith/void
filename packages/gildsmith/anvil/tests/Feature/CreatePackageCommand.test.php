<?php

declare(strict_types=1);

function deleteDirectory(string $path): void
{
    if (! is_dir($path)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );

    foreach ($items as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }

    rmdir($path);
}

function testPath(string $first, string ...$segments): string
{
    $path = rtrim($first, DIRECTORY_SEPARATOR);

    foreach ($segments as $segment) {
        $path .= DIRECTORY_SEPARATOR.trim($segment, DIRECTORY_SEPARATOR);
    }

    return $path;
}

beforeEach(function (): void {
    $this->packagesPath = dirname(__DIR__, 4);
    $this->vendorName = 'anvil-test-'.bin2hex(random_bytes(4));
    $this->packageName = 'audit-log-'.bin2hex(random_bytes(4));
    $this->createdPaths = [];
});

afterEach(function (): void {
    foreach ($this->createdPaths as $path) {
        deleteDirectory($path);
    }
});

it('creates a package from blueprint packages', function (): void {
    $this->artisan('anvil:create-package')
        ->expectsQuestion('Vendor name', $this->vendorName)
        ->expectsQuestion('Package name', $this->packageName)
        ->expectsQuestion('Author name', 'Jane Developer')
        ->assertSuccessful();

    $packagePath = testPath($this->packagesPath, $this->vendorName, $this->packageName);
    $this->createdPaths[] = testPath($this->packagesPath, $this->vendorName);

    expect(is_dir($packagePath))->toBeTrue()
        ->and(file_exists(testPath($packagePath, '.github', 'workflows', 'lint.yml')))->toBeTrue()
        ->and(file_exists(testPath($packagePath, 'database', 'factories', '.gitkeep')))->toBeTrue()
        ->and(file_exists(testPath($packagePath, 'database', 'migrations', '.gitkeep')))->toBeTrue()
        ->and(file_exists(testPath($packagePath, 'tests', 'Architecture', 'Presets.test.php')))->toBeTrue()
        ->and(file_exists(testPath($packagePath, 'tests', 'Access', '.gitkeep')))->toBeTrue()
        ->and(file_exists(testPath($packagePath, '.github', 'workflows', 'lint.yml.blueprint')))->toBeFalse()
        ->and(file_exists(testPath($packagePath, 'composer.json.blueprint')))->toBeFalse()
        ->and(file_exists(testPath($packagePath, '.procedure')))->toBeFalse()
        ->and(file_get_contents(testPath($packagePath, '.github', 'workflows', 'lint.yml')))->toContain('${{ matrix.php }}')
        ->and(json_decode(file_get_contents(testPath($packagePath, 'composer.json')), true))->toBeArray()
        ->and(file_get_contents(testPath($packagePath, 'composer.json')))->toContain('"name": "'.$this->vendorName.'/'.$this->packageName.'"')
        ->and(file_get_contents(testPath($packagePath, 'composer.json')))->toContain('"name": "Jane Developer"')
        ->and(file_get_contents(testPath($packagePath, 'composer.json')))->not->toContain('email')
        ->and(file_get_contents(testPath($packagePath, 'src', 'Providers', 'AppServiceProvider.php')))->toContain('namespace AnvilTest'.str_replace('-', '', ucwords(substr($this->vendorName, strlen('anvil-test-')), '-')).'\AuditLog'.str_replace('-', '', ucwords(substr($this->packageName, strlen('audit-log-')), '-')).'\Providers;')
        ->and(file_get_contents(testPath($packagePath, 'routes', 'api.php')))->toBe('<?php

declare(strict_types=1);

//
');
});

it('uses the default vendor when none is provided', function (): void {
    $packageName = 'anvil-default-'.bin2hex(random_bytes(4));

    $this->artisan('anvil:create-package')
        ->expectsQuestion('Vendor name', null)
        ->expectsQuestion('Package name', $packageName)
        ->expectsQuestion('Author name', 'Default Vendor Author')
        ->assertSuccessful();

    $packagePath = testPath($this->packagesPath, 'gildsmith', $packageName);
    $this->createdPaths[] = $packagePath;

    expect(file_get_contents(testPath($packagePath, 'composer.json')))->toContain('"name": "gildsmith/'.$packageName.'"');
});

it('rolls back a partially created package', function (): void {
    $variables = new Gildsmith\Anvil\Blueprints\BlueprintVariables(
        $this->vendorName,
        $this->packageName,
        'Rollback Author',
    );
    $creator = new Gildsmith\Anvil\Blueprints\BlueprintPackageDirectoryCreator;

    $creator->run($variables);
    file_put_contents(testPath($variables->packagePath, 'partial.txt'), 'partial');
    $creator->rollback();

    expect(file_exists($variables->packagePath))->toBeFalse()
        ->and(file_exists(dirname($variables->packagePath)))->toBeFalse();
});

it('refuses to overwrite an existing package', function (): void {
    $packagePath = testPath($this->packagesPath, $this->vendorName, 'existing-package');
    mkdir($packagePath, 0775, true);
    $this->createdPaths[] = testPath($this->packagesPath, $this->vendorName);

    $this->artisan('anvil:create-package')
        ->expectsQuestion('Vendor name', $this->vendorName)
        ->expectsQuestion('Package name', 'existing-package')
        ->expectsQuestion('Author name', 'Existing Author')
        ->assertFailed()
        ->expectsOutputToContain('already exists');
});

it('fails fast when package name is invalid', function (): void {
    $this->artisan('anvil:create-package')
        ->expectsQuestion('Vendor name', 'gildsmith')
        ->expectsQuestion('Package name', 'Invalid Name')
        ->assertFailed()
        ->expectsOutputToContain('package name must be a valid Composer name segment');
});

it('fails fast when vendor name is invalid', function (): void {
    $this->artisan('anvil:create-package')
        ->expectsQuestion('Vendor name', 'Invalid Vendor')
        ->assertFailed()
        ->expectsOutputToContain('vendor name must be a valid Composer name segment');
});

it('requires an author name', function (): void {
    $this->artisan('anvil:create-package')
        ->expectsQuestion('Vendor name', $this->vendorName)
        ->expectsQuestion('Package name', 'missing-author')
        ->expectsQuestion('Author name', '')
        ->assertFailed()
        ->expectsOutputToContain('author name is required');
});
