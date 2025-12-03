<?php

namespace Gildsmith\Contract\Order;

use Gildsmith\Contract\Cart\CartInterface;
use Gildsmith\Contract\Order\StatusInterface;
use Gildsmith\Contract\Payment\PaymentInterface;
use Gildsmith\Contract\Shipping\ShippingInterface;
use Gildsmith\Contract\User\CustomerInterface;

/**
 * @property int $id
 * @property CartInterface $cart
 * @property CustomerInterface $customer
 * @property PaymentInterface $payment
 * @property ShippingInterface $shipping
 * @property InvoiceInterface $invoice
 */
interface OrderInterface
{
    // Ordering
    public function place();

    public function reorder();

    public function cancel();

    // Status management
    public function setStatus(string $type, StatusInterface $status);

    public function getStatus(string $type);
}
