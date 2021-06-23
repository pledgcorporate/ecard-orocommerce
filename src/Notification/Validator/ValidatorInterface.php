<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\Notification\Validator;

interface ValidatorInterface
{
    public function supports(array $content): bool;

    public function validate(array $content): bool;
}
