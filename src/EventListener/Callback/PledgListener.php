<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\EventListener\Callback;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Event\AbstractCallbackEvent;
use Oro\Bundle\PaymentBundle\Event\CallbackErrorEvent;
use Oro\Bundle\PaymentBundle\Event\CallbackNotifyEvent;
use Oro\Bundle\PaymentBundle\Event\CallbackReturnEvent;
use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Pledg\Bundle\PaymentBundle\Method\PledgMethod;
use Pledg\Bundle\PaymentBundle\Notification\Collector\ValidatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PledgListener
{
    private const PLEDG_RESULT = 'pledg_result';
    private const PLEDG_ERROR = 'pledg_error';

    /** @var PaymentMethodProviderInterface */
    private $paymentMethodProvider;

    /** @var RequestStack */
    private $requestStack;

    /** @var ValidatorInterface */
    private $notificationValidator;

    public function __construct(
        PaymentMethodProviderInterface $paymentMethodProvider,
        RequestStack $requestStack,
        ValidatorInterface $validator
    )
    {
        $this->paymentMethodProvider = $paymentMethodProvider;
        $this->requestStack = $requestStack;
        $this->notificationValidator = $validator;
    }

    public function onReturn(CallbackReturnEvent $event): void
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction) {
            return;
        }

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentTransaction->getPaymentMethod())) {
            return;
        }

        if (!isset($event->getData()[self::PLEDG_RESULT])) {
            $event->markFailed();
            return;
        }

        $this->complete($event, $paymentTransaction);
    }

    public function onError(CallbackErrorEvent $event): void
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction) {
            return;
        }

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentTransaction->getPaymentMethod())) {
            return;
        }

        $message = $this->getMessage($event);
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request || null === $message) {
            return;
        }

        $request->getSession()->getFlashBag()->add('error', json_encode($message));
    }

    public function onNotify(CallbackNotifyEvent $event): void
    {
        $paymentTransaction = $event->getPaymentTransaction();

        if (!$paymentTransaction) {
            return;
        }

        if (false === $this->paymentMethodProvider->hasPaymentMethod($paymentTransaction->getPaymentMethod())) {
            return;
        }

        if (false === $this->notificationValidator->validate($event->getData())) {
            $event->markFailed();
            return;
        }

        $this->complete($event, $paymentTransaction);
    }

    private function complete(AbstractCallbackEvent $event, PaymentTransaction $paymentTransaction): void
    {
        $responseDataFilledWithEventData = array_replace($paymentTransaction->getResponse(), $event->getData());
        $paymentTransaction->setResponse($responseDataFilledWithEventData);

        $paymentMethod = $this->paymentMethodProvider->getPaymentMethod($paymentTransaction->getPaymentMethod());
        $paymentMethod->execute(PledgMethod::COMPLETE, $paymentTransaction);

        $event->markSuccessful();
    }

    private function getMessage(AbstractCallbackEvent $event)
    {
        if (!isset($event->getData()[self::PLEDG_ERROR])) {
            return null;
        }
        return json_decode(
            $event->getData()[self::PLEDG_ERROR],
            true,
            512,
            JSON_THROW_ON_ERROR
        )['message'] ?? null;
    }
}
