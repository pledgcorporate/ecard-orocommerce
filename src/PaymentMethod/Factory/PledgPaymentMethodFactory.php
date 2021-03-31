<?php


namespace Pledg\Bundle\PaymentBundle\PaymentMethod\Factory;


use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\PaymentMethod\PledgMethod;

class PledgPaymentMethodFactory implements PledgPaymentMethodFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(PledgConfigInterface $config)
    {
        return new PledgMethod($config);
    }
}
