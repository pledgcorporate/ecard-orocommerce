<?php


namespace Pledg\Bundle\PaymentBundle\PaymentMethod\Config;


use Oro\Bundle\PaymentBundle\Method\Config\PaymentConfigInterface;

interface PledgConfigInterface extends PaymentConfigInterface
{
    /**
     * @return string
     */
    public function getClientIdentifier();

    /**
     * @return string
     */
    public function getClientSecret();
}
