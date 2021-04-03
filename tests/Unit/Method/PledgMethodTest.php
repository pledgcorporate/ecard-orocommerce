<?php

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Method;

use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use PHPUnit\Framework\TestCase;
use Pledg\Bundle\PaymentBundle\Method\PledgMethod;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config\PledgConfigBuilder;

class PledgMethodTest extends TestCase
{
    public function testSupportsOnlyPurchaseAction(): void
    {
        $method = $this->buildMethod();

        self::assertTrue($method->supports(PaymentMethodInterface::PURCHASE));
        self::assertFalse($method->supports(PaymentMethodInterface::PENDING));
        self::assertFalse($method->supports(PaymentMethodInterface::AUTHORIZE));
        self::assertFalse($method->supports(PaymentMethodInterface::CANCEL));
        self::assertFalse($method->supports(PaymentMethodInterface::CHARGE));
        self::assertFalse($method->supports(PaymentMethodInterface::CAPTURE));
        self::assertFalse($method->supports(PaymentMethodInterface::INVOICE));
        self::assertFalse($method->supports(PaymentMethodInterface::VALIDATE));

    }

    public function buildMethod(): PledgMethod
    {
        return new PledgMethod((new PledgConfigBuilder())
            ->withDefaultValues()
            ->build()
        );
    }
}
