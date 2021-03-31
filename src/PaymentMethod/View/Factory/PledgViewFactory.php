<?php


namespace Pledg\Bundle\PaymentBundle\PaymentMethod\View\Factory;


use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\PaymentMethod\View\PledgView;

class PledgViewFactory implements PledgViewFactoryInterface
{
    public function create(PledgConfigInterface $config)
    {
        return new PledgView($config);
    }

}
