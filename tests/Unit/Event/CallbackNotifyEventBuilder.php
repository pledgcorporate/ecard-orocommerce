<?php

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Event;


use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Event\CallbackNotifyEvent;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\StandardContentBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Oro\Entity\PaymentTransactionBuilder;

class CallbackNotifyEventBuilder
{
    /** @var array */

    private $data = [];

    /** @var PaymentTransaction */
    private $paymentTransaction;

    public function __construct()
    {
        $this->paymentTransaction = (new PaymentTransactionBuilder())->build();
    }

    public function withValidStandardContent(): self
    {
        $this->data = (new StandardContentBuilder())->build();

        return $this;
    }

    public function withInvalidStandardContent(): self
    {
        $this->data = (new StandardContentBuilder())->withReference('')->build();

        return $this;
    }

    public function build(): CallbackNotifyEvent
    {
        $event = new CallbackNotifyEvent($this->data);
        $event->setPaymentTransaction($this->paymentTransaction);

        return $event;
    }
}
