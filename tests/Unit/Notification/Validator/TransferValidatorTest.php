<?php

declare(strict_types=1);

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\Validator;

use PHPUnit\Framework\TestCase;
use Pledg\Bundle\PaymentBundle\JWT\HS256Handler;
use Pledg\Bundle\PaymentBundle\Notification\Validator\NotSupportedException;
use Pledg\Bundle\PaymentBundle\Notification\Validator\ValidatorInterface;
use Pledg\Bundle\PaymentBundle\ValueObject\Reference;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config\PledgConfigBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\StandardContentBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\TransferContentBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Oro\Entity\PaymentTransactionBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Provider\PaymentProviderBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Sylius\Model\PaymentBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Sylius\Repository\InMemoryPaymentRepository;
use Tests\Pledg\Bundle\PaymentBundle\Unit\ValueObject\MerchantBuilder;

class TransferValidatorTest extends TestCase
{
    public function testValidatesWithInvalidNumberOfData(): void
    {
        $content = (new StandardContentBuilder())->build();
        $validator = $this->buildValidator();

        $this->expectException(NotSupportedException::class);

        $validator->validate($content);
    }

    public function testValidatesWithInvalidContent(): void
    {
        $content = (new TransferContentBuilder())->withInvalidSignature()->build();
        $validator = $this->buildValidator();

        self::assertFalse($validator->validate($content));
    }

    public function testValidatesWithValidContent(): void
    {
        $content = (new TransferContentBuilder())->withValidContent()->build();
        $validator = $this->buildValidator();

        self::assertTrue($validator->validate($content));
    }

    private function buildValidator(): ValidatorInterface
    {
        return (new TransferValidatorBuilder())
            ->withPaymentTransaction((new PaymentTransactionBuilder())->build())
            ->withConfig((new PledgConfigBuilder())->withSecret('secret')->build())
            ->build();
    }
}
