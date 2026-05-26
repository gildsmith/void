<?php

declare(strict_types=1);

namespace Gildsmith\Support\Model\Contracts;

interface HasValidationRulesInterface
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function getValidationRules(): array;

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getCreateValidationRules(): array;

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getUpdateValidationRules(): array;

    /**
     * @return list<string>
     */
    public function getRequiredForCreate(): array;

    /**
     * @return list<string>
     */
    public function getRequiredForUpdate(): array;
}
