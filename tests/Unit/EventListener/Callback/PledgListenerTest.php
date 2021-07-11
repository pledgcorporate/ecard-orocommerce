<?php

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use PHPUnit\Framework\TestCase;
use Pledg\Bundle\PaymentBundle\EventListener\Callback\PledgListener;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfig;
use Pledg\Bundle\PaymentBundle\Notification\Validator\CompositeValidator;
use Pledg\Bundle\PaymentBundle\Notification\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Event\CallbackNotifyEventBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config\PledgConfigBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\PledgMethodBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\Validator\StandardValidatorBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\TransferContentBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\Validator\TransferValidatorBuilder;

class PledgListenerTest extends TestCase
{
    public function testStandardNotificationSuccessfully(): void
    {
        $event = (new CallbackNotifyEventBuilder())
            ->withValidStandardContent()
            ->build();
        $listener = $this->buildListenerWithEventAndSecret($event, 'SECRET');

        $listener->onNotify($event);

        $this->assertEventIsMarkSuccessful($event);
    }

    public function testStandardNotificationFailed(): void
    {
        $event = (new CallbackNotifyEventBuilder())
            ->withInvalidStandardContent()
            ->build();
        $listener = $this->buildListenerWithEventAndSecret($event, 'SECRET');

        $listener->onNotify($event);

        $this->assertEventIsMarkFailed($event);
    }

    public function testTransferNotificationSuccessfully(): void
    {
        $event = (new CallbackNotifyEventBuilder())
            ->withData((new TransferContentBuilder())
                ->withValidContent()
                ->build()
            )
            ->build();
        $listener = $this->buildListenerWithEventAndSecret($event, 'secret');

        $listener->onNotify($event);

        $this->assertEventIsMarkSuccessful($event);
    }

    private function assertEventIsMarkSuccessful(AbstractCallbackEvent $event): void
    {
        self::assertSame(Response::HTTP_OK, $event->getResponse()->getStatusCode());
    }

    private function assertEventIsMarkFailed(AbstractCallbackEvent $event): void
    {
        self::assertSame(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
    }

    private function buildListenerWithEventAndSecret(AbstractCallbackEvent $event, string $secret): PledgListener
    {
        $config = (new PledgConfigBuilder())
            ->withDefaultValues()
            ->withSecret($secret)
            ->build();
        return (new PledgListenerBuilder())
            ->withPaymentMethod((new PledgMethodBuilder())
                ->withConfig($config)
                ->build())
            ->withValidator($this->buildValidators($config, $event->getPaymentTransaction()))
            ->build();
    }

    private function buildValidators(PledgConfig $config, PaymentTransaction $paymentTransaction): ValidatorInterface
    {
        return new CompositeValidator(new \ArrayIterator([
            (new StandardValidatorBuilder())
                ->withPaymentTransaction($paymentTransaction)
                ->withConfig($config)
                ->build(),
            (new TransferValidatorBuilder())
                ->withPaymentTransaction($paymentTransaction)
                ->withConfig($config)
                ->build()
        ]));
    }
}
