<?php

namespace Pledg\Bundle\PaymentBundle\PaymentMethod\View\Factory;

use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfigInterface;

interface PledgViewFactoryInterface
{
    public function create(PledgConfigInterface $config);
}
