<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\RedirectUrl;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;

interface ParametersFactoryInterface
{
    public function fromPaymentTransactionAndConfig(
        PaymentTransaction $paymentTransaction,
        PledgConfigInterface $config
    ): ParametersInterface;
}
