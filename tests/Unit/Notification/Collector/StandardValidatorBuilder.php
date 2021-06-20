<?php

declare(strict_types=1);

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Notification\Collector;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfig;
use Pledg\Bundle\PaymentBundle\Method\Config\Provider\PledgConfigProviderInterface;
use Pledg\Bundle\PaymentBundle\Notification\Collector\StandardValidator;
use Pledg\Bundle\PaymentBundle\Notification\Collector\ValidatorInterface;
use Pledg\Bundle\PaymentBundle\Provider\PaymentTransactionProviderInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class StandardValidatorBuilder
{
    /** @var PaymentTransactionProviderInterface|ObjectProphecy */
    protected $paymentTransactionProvider;

    /** @var PledgConfigProviderInterface|ObjectProphecy */
    protected $pledgConfigProvider;

    public function __construct()
    {
        $this->paymentTransactionProvider = (new Prophet())
            ->prophesize(PaymentTransactionProviderInterface::class);
        $this->pledgConfigProvider = (new Prophet())->prophesize(PledgConfigProviderInterface::class);
    }

    public function withPaymentTransaction(PaymentTransaction $paymentTransaction): self
    {
        $this->paymentTransactionProvider->getByReference(Argument::any())->willReturn($paymentTransaction);

        return $this;
    }

    public function withConfig(PledgConfig $config): self
    {
        $this->pledgConfigProvider->getPaymentConfig(Argument::any())->willReturn($config);

        return $this;
    }

    public function build(): ValidatorInterface
    {
        return new StandardValidator($this->paymentTransactionProvider->reveal(), $this->pledgConfigProvider->reveal());
    }
}
