<?php


namespace Pledg\Bundle\PaymentBundle\Method\Factory;


use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Method\PledgMethod;

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
