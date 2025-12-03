<?php

namespace Gildsmith\Contract\Cart;

use Gildsmith\Contract\Inventory\QuantityInterface;
use Gildsmith\Contract\Pricing\PriceInterface;
use Gildsmith\Contract\Product\ProductInterface;

/**
 * Represents a product in a cart.
 *
 * @property CartInterface $cart
 * @property ProductInterface $product
 * @property QuantityInterface $quantity
 * @property PriceInterface $price
 */
interface CartProductInterface {}
