<?php


namespace Pledg\Bundle\PaymentBundle\Provider;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

interface PaymentTransactionProviderInterface
{
    public function getByReference(string $reference): ?PaymentTransaction;
}
