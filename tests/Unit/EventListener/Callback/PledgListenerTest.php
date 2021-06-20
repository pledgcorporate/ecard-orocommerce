<?php

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use PHPUnit\Framework\TestCase;
use Pledg\Bundle\PaymentBundle\EventListener\Callback\PledgListener;
use Pledg\Bundle\PaymentBundle\Notification\Collector\StandardValidator;
use Symfony\Component\HttpFoundation\Response;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Event\CallbackNotifyEventBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config\PledgConfigBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\PledgMethodBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\Collector\StandardValidatorBuilder;

class PledgListenerTest extends TestCase
{
    public function testStandardNotificationSuccessfully(): void
    {
        $event = (new CallbackNotifyEventBuilder())
            ->withValidStandardContent()
            ->build();
        $listener = $this->buildListenerWithEvent($event);

        $listener->onNotify($event);

        $this->assertEventIsMarkSuccessful($event);
    }

    public function testStandardNotificationFailed(): void
    {
        $event = (new CallbackNotifyEventBuilder())
            ->withInvalidStandardContent()
            ->build();
        $listener = $this->buildListenerWithEvent($event);

        $listener->onNotify($event);

        $this->assertEventIsMarkFailed($event);
    }

    private function assertEventIsMarkSuccessful(AbstractCallbackEvent $event): void
    {
        self::assertSame(Response::HTTP_OK, $event->getResponse()->getStatusCode());
    }

    private function assertEventIsMarkFailed(AbstractCallbackEvent $event): void
    {
        self::assertSame(Response::HTTP_FORBIDDEN, $event->getResponse()->getStatusCode());
    }

    private function buildListenerWithEvent(AbstractCallbackEvent $event): PledgListener
    {
        $config = (new PledgConfigBuilder())
            ->withDefaultValues()
            ->build();
        return (new PledgListenerBuilder())
            ->withPaymentMethod((new PledgMethodBuilder())
                ->withConfig($config)
                ->build())
            ->withValidator((new StandardValidatorBuilder())
                ->withPaymentTransaction($event->getPaymentTransaction())
                ->withConfig($config)
                ->build()
            )
            ->build();
    }
}
