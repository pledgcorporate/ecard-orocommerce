<?php

namespace Pledg\Bundle\PaymentBundle\Method\Factory;


use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;

interface PledgPaymentMethodFactoryInterface
{
    /**
     * @param PledgConfigInterface $config
     * @return PaymentMethodInterface
     */
    public function create(PledgConfigInterface $config);
}
