<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\RedirectUrl;

interface ParametersInterface
{
    public function toArray(): array;
}
