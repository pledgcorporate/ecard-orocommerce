<?php

namespace Pledg\Bundle\PaymentBundle\Provider;

use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

interface OrderProviderInterface
{
    public function findByPaymentTransaction(PaymentTransaction $paymentTransaction): Order;
}
