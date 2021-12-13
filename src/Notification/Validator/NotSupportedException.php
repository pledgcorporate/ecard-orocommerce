<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\Notification\Validator;

class NotSupportedException extends \InvalidArgumentException implements CollectorException
{
    public static function fromContent(array $content): self
    {
        return new self(sprintf(
            'There is no notification validator that supports %s',
            json_encode($content, \JSON_THROW_ON_ERROR)
        ));
    }
}
