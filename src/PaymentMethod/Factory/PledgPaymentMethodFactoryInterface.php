<?php

namespace Pledg\Bundle\PaymentBundle\PaymentMethod\Factory;


use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfigInterface;

interface PledgPaymentMethodFactoryInterface
{
    /**
     * @param PledgConfigInterface $config
     * @return PaymentMethodInterface
     */
    public function create(PledgConfigInterface $config);
}
