<?php

namespace Pledg\Bundle\PaymentBundle\Method\View\Factory;

use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;

interface PledgViewFactoryInterface
{
    public function create(PledgConfigInterface $config);
}
