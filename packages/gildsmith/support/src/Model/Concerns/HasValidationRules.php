<?php

declare(strict_types=1);

namespace Gildsmith\Support\Model\Concerns;

use Gildsmith\Support\Model\Contracts\HasValidationRulesInterface;
use Gildsmith\Support\Utils\ValidationRules;
use Illuminate\Database\Eloquent\Attributes\Boot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use LogicException;

/**
 * @mixin Model
 *
 * @phpstan-require-extends Model
 * @phpstan-require-implements HasValidationRulesInterface
 */
trait HasValidationRules
{
    #[Boot]
    public static function bootValidationRules(): void
    {
        static::creating(function (self $model): void {
            Validator::make($model->getAttributes(), $model->getCreateValidationRules())->validate();
        });

        static::updating(function (self $model): void {
            Validator::make($model->getAttributes(), $model->getUpdateValidationRules())->validate();
        });
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getValidationRules(): array
    {
        $rules = property_exists($this, 'rules')
            ? $this->rules
            : [];

        $rules = $this->mergeDefaultValidationRules($rules);
        $rules = $this->normalizeValidationRules($rules);

        $this->ensureValidationRulesDoNotRequire($rules);

        return $rules;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getCreateValidationRules(): array
    {
        return $this->validationRulesWithRequired($this->getRequiredForCreate());
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function getUpdateValidationRules(): array
    {
        return $this->validationRulesWithRequired($this->getRequiredForUpdate());
    }

    /**
     * @return list<string>
     */
    public function getRequiredForCreate(): array
    {
        $required = property_exists($this, 'requiredForCreate')
            ? $this->requiredForCreate
            : [];

        return $this->mergeDefaultRequiredAttributes($required, $this->defaultRequiredForCreate());
    }

    /**
     * @return list<string>
     */
    public function getRequiredForUpdate(): array
    {
        $required = property_exists($this, 'requiredForUpdate')
            ? $this->requiredForUpdate
            : [];

        return $this->mergeDefaultRequiredAttributes($required, $this->defaultRequiredForUpdate());
    }

    /**
     * @param  list<string>  $required
     * @return array<string, array<int, mixed>>
     */
    protected function validationRulesWithRequired(array $required): array
    {
        $rules = $this->getValidationRules();

        foreach ($required as $attribute) {
            $rules[$attribute] ??= [];

            array_unshift($rules[$attribute], 'required');
        }

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $rules
     * @return array<string, mixed>
     */
    protected function mergeDefaultValidationRules(array $rules): array
    {
        foreach ($this->defaultValidationRules() as $trait => $defaults) {
            if (! in_array($trait, class_uses_recursive($this), true)) {
                continue;
            }

            foreach ($defaults as $attribute => $attributeRules) {
                if (array_key_exists($attribute, $rules)) {
                    continue;
                }

                $rules[$attribute] = $this->normalizeValidationRule($attributeRules);
            }
        }

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $rules
     * @return array<string, array<int, mixed>>
     */
    protected function normalizeValidationRules(array $rules): array
    {
        foreach ($rules as $attribute => $attributeRules) {
            $rules[$attribute] = $this->normalizeValidationRule($attributeRules);
        }

        return $rules;
    }

    /**
     * @return array<int, mixed>
     */
    protected function normalizeValidationRule(mixed $rules): array
    {
        if (is_string($rules)) {
            return $rules === ''
                ? []
                : explode('|', $rules);
        }

        if (is_array($rules)) {
            return $rules;
        }

        return [$rules];
    }

    /**
     * @param  list<string>  $required
     * @param  array<class-string, list<string>>  $defaults
     * @return list<string>
     */
    protected function mergeDefaultRequiredAttributes(array $required, array $defaults): array
    {
        foreach ($defaults as $trait => $attributes) {
            if (! in_array($trait, class_uses_recursive($this), true)) {
                continue;
            }

            array_push($required, ...$attributes);
        }

        return array_values(array_unique($required));
    }

    /**
     * Map model concerns to validation rules that should be applied when the
     * current model uses the concern and does not already define the rule.
     *
     * This keeps common Gildsmith conventions, such as HasCode requiring a
     * code-shaped value, out of every model class. Override this method when a
     * package introduces its own concern-level validation defaults.
     *
     * @return array<class-string, array<string, mixed>>
     */
    protected function defaultValidationRules(): array
    {
        return [
            HasCode::class => [
                'code' => ValidationRules::CODE,
            ],
        ];
    }

    /**
     * Map model concerns to attributes that should be required while creating
     * a model that uses the concern.
     *
     * The attributes listed here are merged with the model's
     * $requiredForCreate property. Override this method when a package-level
     * concern needs to make an attribute mandatory on creation.
     *
     * @return array<class-string, list<string>>
     */
    protected function defaultRequiredForCreate(): array
    {
        return [
            HasCode::class => ['code'],
        ];
    }

    /**
     * Map model concerns to attributes that should be required while updating
     * a model that uses the concern.
     *
     * Most defaults should not require fields on update, because PATCH-style
     * payloads are usually partial. Override this only when a concern truly
     * needs an attribute to be present for every update.
     *
     * @return array<class-string, list<string>>
     */
    protected function defaultRequiredForUpdate(): array
    {
        return [];
    }

    /**
     * @param  array<string, array<int, mixed>>  $rules
     */
    protected function ensureValidationRulesDoNotRequire(array $rules): void
    {
        foreach ($rules as $attribute => $attributeRules) {
            if (! in_array('required', $attributeRules, true)) {
                continue;
            }

            throw new LogicException(sprintf(
                'Validation rules for [%s] should not contain [required]. Use $requiredForCreate or $requiredForUpdate instead.',
                $attribute,
            ));
        }
    }
}
