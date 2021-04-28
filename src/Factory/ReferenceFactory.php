<?php

namespace Pledg\Bundle\PaymentBundle\Factory;

use Pledg\Bundle\PaymentBundle\ValueObject\Reference;
use Pledg\Bundle\PaymentBundle\ValueObject\ReferenceInterface;

class ReferenceFactory implements ReferenceFactoryInterface
{
    public function fromOrderId(int $orderId): ReferenceInterface
    {
        return new Reference($orderId, uniqid($orderId, false));
    }
}
