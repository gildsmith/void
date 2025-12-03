<?php

namespace Gildsmith\Contract\Context;

use Illuminate\Support\Collection;

/**
 * This interface represents online store instance.
 * Used primarily for context purpose.
 *
 * @property int $id
 * @property string $name
 * @property array<int, string> $domains
 * @property Collection<int, CurrencyInterface> $currencies
 * @property Collection<int, LanguageInterface> $languages
 * @property CurrencyInterface $defaultCurrency
 * @property LanguageInterface $defaultLanguage
 */
interface WebsiteInterface
{
    //
}
