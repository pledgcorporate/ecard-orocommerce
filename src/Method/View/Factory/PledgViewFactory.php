<?php


namespace Pledg\Bundle\PaymentBundle\Method\View\Factory;


use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Method\View\PledgView;

class PledgViewFactory implements PledgViewFactoryInterface
{
    public function create(PledgConfigInterface $config)
    {
        return new PledgView($config);
    }

}
