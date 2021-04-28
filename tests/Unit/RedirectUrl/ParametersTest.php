<?php

declare(strict_types=1);

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\RedirectUrl;

use Oro\Bundle\AddressBundle\Entity\AddressType;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config\PledgConfigBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Oro\Entity\CustomerAddressBuilder;

class ParametersTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function it_builds_param_with_valid_RedirectUrl_request(): void
    {

        $parameters = (new ParametersBuilder())
            ->withLocale('fr_FR')
            ->withConfig(
                (new PledgConfigBuilder())
                    ->withDefaultValues()
                    ->build()
            )
            ->withBillingAddress(
                (new CustomerAddressBuilder())
                    ->withStreet('street')
                    ->withCity('Paris')
                    ->withPostalCode('75001')
                    ->build()
            )
            ->withShippingAddress(
                (new CustomerAddressBuilder())
                    ->withStreet('street')
                    ->withCity('Paris')
                    ->withPostalCode('75001')
                    ->build()
            )
            ->build()
            ->toArray();

        self::assertArrayHasKey('merchantUid', $parameters);
        self::assertArrayHasKey('lang', $parameters);
        self::assertArrayHasKey('title', $parameters);
        self::assertArrayHasKey('reference', $parameters);
        self::assertArrayHasKey('amountCents', $parameters);
        self::assertArrayHasKey('currency', $parameters);
        self::assertArrayHasKey('firstName', $parameters);
        self::assertArrayHasKey('lastName', $parameters);
        self::assertArrayHasKey('email', $parameters);
        self::assertArrayHasKey('phoneNumber', $parameters);
        self::assertArrayHasKey('countryCode', $parameters);
        self::assertArrayHasKey('redirectUrl', $parameters);
        self::assertArrayHasKey('cancelUrl', $parameters);
        self::assertArrayHasKey('paymentNotificationUrl', $parameters);
        self::assertArrayHasKey('address', $parameters);
        //self::assertArrayHasKey('street', $parameters['address']);
        //self::assertArrayHasKey('city', $parameters['address']);
        //self::assertArrayHasKey('zipcode', $parameters['address']);
        //self::assertArrayHasKey('country', $parameters['address']);
        self::assertArrayHasKey('shippingAddress', $parameters);
        //self::assertArrayHasKey('street', $parameters['shippingAddress']);
        //self::assertArrayHasKey('city', $parameters['shippingAddress']);
        //self::assertArrayHasKey('zipcode', $parameters['shippingAddress']);
        //self::assertArrayHasKey('country', $parameters['shippingAddress']);
    }
}
