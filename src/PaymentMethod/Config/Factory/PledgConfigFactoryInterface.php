<?php

namespace Pledg\Bundle\PaymentBundle\PaymentMethod\Config\Factory;

use Pledg\Bundle\PaymentBundle\Entity\PledgSettings;

interface PledgConfigFactoryInterface
{
    public function create(PledgSettings $settings);
}
