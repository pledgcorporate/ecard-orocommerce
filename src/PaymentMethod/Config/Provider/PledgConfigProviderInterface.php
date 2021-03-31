<?php

namespace Pledg\Bundle\PaymentBundle\PaymentMethod\Config\Provider;

use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfigInterface;

interface PledgConfigProviderInterface
{
    /**
     * @return PledgConfigInterface[]
     */
    public function getPaymentConfigs();

    /**
     * @param string $identifier
     * @return PledgConfigInterface|null
     */
    public function getPaymentConfig($identifier);

    /**
     * @param string $identifier
     * @return bool
     */
    public function hasPaymentConfig($identifier);
}
