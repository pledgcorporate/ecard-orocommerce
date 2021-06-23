<?php

declare(strict_types=1);

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\Collector;

use PHPUnit\Framework\TestCase;
use Pledg\Bundle\PaymentBundle\Notification\Collector\ValidatorInterface;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config\PledgConfigBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\StandardContentBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Oro\Entity\PaymentTransactionBuilder;

class StandardValidatorTest extends TestCase
{
    public function testSupportsWithInvalidNumberOfData(): void
    {
        $validator = (new StandardValidatorBuilder())
            ->build();

        self::assertFalse($validator->supports(['signature']));
    }

    public function testSupportsWithValidContent(): void
    {
        $content = (new StandardContentBuilder())->build();
        $validator = (new StandardValidatorBuilder())
            ->build();

        self::assertTrue($validator->supports($content));
    }

    public function testValidatesWithInvalidContent(): void
    {
        $content = (new StandardContentBuilder())->withReference('')->build();
        $validator = $this->buildValidator();

        self::assertFalse($validator->validate($content));
    }

    public function testValidatesWithValidContent(): void
    {
        $content = (new StandardContentBuilder())->build();
        $validator = $this->buildValidator();

        self::assertTrue($validator->validate($content));
    }

    public function testValidatesWithError(): void
    {
        $content = (new StandardContentBuilder())
            ->withError('error')
            ->withSignature('3CBB6A528621A7BAE2017814256986AF29B32725302B7075315ADF5577C8777E')
            ->build();
        $validator = $this->buildValidator();

        self::assertFalse($validator->validate($content));
    }

    public function testValidatesWithIncorrectStatus(): void
    {
        $content = (new StandardContentBuilder())
            ->withStatus('failed')
            ->withSignature('F40940F5FC30078D5E19470774F63F47FB577E56D66A49813D1E47490586CBBF')
            ->build();
        $validator = $this->buildValidator();

        self::assertFalse($validator->validate($content));
    }

    private function buildValidator(): ValidatorInterface
    {
        return (new StandardValidatorBuilder())
            ->withPaymentTransaction((new PaymentTransactionBuilder())->build())
            ->withConfig((new PledgConfigBuilder())->withDefaultValues()->build())
            ->build();
    }
}
