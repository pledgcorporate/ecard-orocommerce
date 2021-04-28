<?php

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Oro\Entity;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

class PaymentTransactionBuilder
{
    public function build(): PaymentTransaction
    {
        return new PaymentTransaction();
    }
}
