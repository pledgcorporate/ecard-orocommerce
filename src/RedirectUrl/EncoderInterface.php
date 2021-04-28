<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\RedirectUrl;

interface EncoderInterface
{
    public function encode(array $parameters, string $secret): string;
}
