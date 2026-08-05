<?php

declare(strict_types=1);

namespace Gildsmith\Anvil\Commands;

use Gildsmith\Anvil\Blueprints\BlueprintFileCopier;
use Gildsmith\Anvil\Blueprints\BlueprintPackageDirectoryCreator;
use Gildsmith\Anvil\Blueprints\BlueprintProcedureProcessor;
use Gildsmith\Anvil\Blueprints\BlueprintRenderer;
use Gildsmith\Anvil\Blueprints\BlueprintVariables;
use Illuminate\Console\Command;
use Throwable;

final class CreatePackageCommand extends Command
{
    protected $signature = 'anvil:create-package';

    protected $description = 'Create a new Gildsmith package from Anvil blueprints.';

    public function handle(): int
    {
        $variables = $this->askForVariables();

        if (! $variables instanceof BlueprintVariables) {
            return self::FAILURE;
        }

        $blueprintPackageDirectoryCreator = new BlueprintPackageDirectoryCreator;
        $blueprintCopier = new BlueprintFileCopier;
        $blueprintRenderer = new BlueprintRenderer;
        $blueprintProcedureProcessor = new BlueprintProcedureProcessor;

        try {
            $blueprintPackageDirectoryCreator->run($variables);
            $blueprintCopier->run($variables);
            $blueprintRenderer->run($variables);
            $blueprintProcedureProcessor->run($variables);
        } catch (Throwable $exception) {
            $blueprintPackageDirectoryCreator->rollback();
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Created package [$variables->composerName] at [$variables->packagePath].");

        return self::SUCCESS;
    }

    private function askForVariables(): ?BlueprintVariables
    {
        $vendor = trim((string) $this->ask('Vendor name', 'gildsmith'));
        $vendor = $vendor === '' ? 'gildsmith' : strtolower($vendor);

        if (! $this->isComposerSegment($vendor)) {
            $this->error('The vendor name must be a valid Composer name segment.');

            return null;
        }

        $package = strtolower(trim((string) $this->ask('Package name')));

        if (! $this->isComposerSegment($package)) {
            $this->error('The package name must be a valid Composer name segment.');

            return null;
        }

        $authorName = trim((string) $this->ask('Author name'));

        if ($authorName === '') {
            $this->error('The author name is required.');

            return null;
        }

        return new BlueprintVariables($vendor, $package, $authorName);
    }

    private function isComposerSegment(string $value): bool
    {
        return preg_match('/^[a-z0-9]+(?:[._-][a-z0-9]+)*$/', $value) === 1;
    }
}
