<?php

namespace Gildsmith\Contract\Cart;

use Gildsmith\Contract\Order\OrderInterface;
use Gildsmith\Contract\Payment\PaymentMethodInterface;
use Gildsmith\Contract\Shipping\ShippingMethodInterface;
use Gildsmith\Contract\User\CustomerInterface;
use Illuminate\Support\Collection;

/**
 * Interface representing cart instance. It consists of all
 * essential properties allowing one to make an order.
 *
 * @property int $id
 * @property CustomerInterface $customer
 * @property Collection<int, CartProductInterface> $products
 * @property PaymentMethodInterface $paymentMethod
 * @property ShippingMethodInterface $shippingMethod
 */
interface CartInterface
{
    /**
     * Transforms the cart into an order instance.
     */
    public function order(): OrderInterface;

    /**
     * Validates whether the cart is eligible for checkout.
     */
    public function validate(): bool;
}
