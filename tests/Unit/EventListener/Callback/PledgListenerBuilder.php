<?php


namespace Tests\Pledg\Bundle\PaymentBundle\Unit\EventListener\Callback;


use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Pledg\Bundle\PaymentBundle\EventListener\Callback\PledgListener;
use Pledg\Bundle\PaymentBundle\Method\PledgMethod;
use Pledg\Bundle\PaymentBundle\Notification\Collector\StandardValidator;
use Pledg\Bundle\PaymentBundle\Notification\Collector\ValidatorInterface;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\HttpFoundation\RequestStack;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config\PledgConfigBuilder;
use Tests\Pledg\Bundle\PaymentBundle\Unit\Method\PledgMethodBuilder;

class PledgListenerBuilder
{
    /** @var PaymentMethodProviderInterface */
    private $paymentMethodProvider;

    /** @var RequestStack */
    private $requestStack;

    /** @var ValidatorInterface */
    private $notificationValidator;

    public function __construct()
    {
        $this->paymentMethodProvider = (new Prophet())->prophesize(PaymentMethodProviderInterface::class);
        $this->requestStack = (new Prophet())->prophesize(RequestStack::class)->reveal();
    }

    public function withPaymentMethod(PledgMethod $method): self
    {
        $this->paymentMethodProvider->hasPaymentMethod(Argument::any())->willReturn(true);
        $this->paymentMethodProvider->getPaymentMethod(Argument::any())->willReturn($method);

        return $this;
    }


    public function withValidator(ValidatorInterface $validator): self
    {
        $this->notificationValidator = $validator;

        return $this;
    }

    public function build(): PledgListener
    {
        return new PledgListener($this->paymentMethodProvider->reveal(), $this->requestStack, $this->notificationValidator);
    }
}
