<?php

declare(strict_types=1);

/**
 * @param class-string $facade
 * @param class-string $model
 * @param array<string, mixed> $createData
 * @param array<string, mixed> $updateData
 */
function itFulfillsTrashableFacadeContract(
    string $facade,
    string $model,
    array $createData = [],
    array $updateData = [],
): void {
    itFulfillsCrudFacadeContract(
        facade: $facade,
        model: $model,
        createData: $createData,
        updateData: $updateData,
    );

    describe($facade, function () use ($facade, $model, $createData, $updateData) {
        describe('configuration', function () use ($facade, $model, $createData) {
            //
        });

        describe('all', function () use ($facade, $model, $createData) {
            //
        });

        describe('find', function () use ($facade, $model, $createData) {
            //
        });

        describe('delete', function () use ($facade, $model, $createData) {
            //
        });

        describe('restore', function () use ($facade, $model, $createData) {
            //
        });

        describe('trashed', function () use ($facade, $model, $createData) {
            //
        });
    });
}
