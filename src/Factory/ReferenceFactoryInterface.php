<?php

namespace Pledg\Bundle\PaymentBundle\Factory;

use Pledg\Bundle\PaymentBundle\ValueObject\ReferenceInterface;

interface ReferenceFactoryInterface
{
    public function fromOrderId(int $orderId): ReferenceInterface;
}
