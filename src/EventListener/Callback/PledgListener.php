<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Event\CallbackErrorEvent;
use Oro\Bundle\PaymentBundle\Event\CallbackReturnEvent;
use Oro\Bundle\PaymentBundle\EventListener\Callback\RedirectListener;

class PledgListener
{
    private const PLEDG_RESULT = 'pledg_result';
    private const PLEDG_ERROR = 'pledg_error';

    /** @var RedirectListener */
    private $listener;

    public function __construct(RedirectListener $listener)
    {
        $this->listener = $listener;
    }

    public function __invoke(CallbackReturnEvent $event)
    {

        if (isset($event->getData()[self::PLEDG_RESULT])) {
            $this->listener->onReturn($event);
        }

        if (isset($event->getData()[self::PLEDG_ERROR])) {
            $this->listener->onError(new CallbackErrorEvent($event->getData()));
        }
    }
}
