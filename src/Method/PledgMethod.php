<?php

namespace Pledg\Bundle\PaymentBundle\Method;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Pledg\Bundle\PaymentBundle\Factory\ReferenceFactoryInterface;
use Pledg\Bundle\PaymentBundle\JWT\HandlerInterface;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\RedirectUrl\ParametersFactoryInterface;
use Pledg\Bundle\PaymentBundle\ValueObject\Reference;

class PledgMethod implements PaymentMethodInterface
{
    public const COMPLETE = 'complete';

    /** @var PledgConfigInterface  */
    private $config;

    /** @var ParametersFactoryInterface */
    private $parametersFactory;

    /** @var ReferenceFactoryInterface */
    private $referenceFactory;

    /** @var HandlerInterface */
    private $encoder;

    public function __construct(
        PledgConfigInterface $config,
        ParametersFactoryInterface $parametersFactory,
        ReferenceFactoryInterface $referenceFactory,
        HandlerInterface $encoder
    )
    {
        $this->config = $config;
        $this->parametersFactory = $parametersFactory;
        $this->referenceFactory = $referenceFactory;
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($action, PaymentTransaction $paymentTransaction)
    {
        if (!$this->supports($action)) {
            throw new \InvalidArgumentException(sprintf('Unsupported action "%s"', $action));
        }

        return $this->{$action}($paymentTransaction);
    }

    protected function purchase(PaymentTransaction $paymentTransaction): array
    {
        $paymentTransaction
            ->setActive(true)
            ->setReference((string) $this->referenceFactory->fromOrderId($paymentTransaction->getEntityIdentifier()));

        $parameters = $this->parametersFactory->fromPaymentTransactionAndConfig($paymentTransaction, $this->config);

        return [
            'purchaseRedirectUrl' => sprintf(
                'https://staging.front.ecard.pledg.co/purchase?signature=%s',
                $this->encoder->encode(['data' => $parameters->toArray()], $this->config->getClientSecret())
            )
        ];
    }

    protected function complete(PaymentTransaction $paymentTransaction): array
    {
        $paymentTransaction
            ->setSuccessful(true)
            ->setActive(false);

        return ['successful' => true];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->config->getPaymentMethodIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(PaymentContextInterface $context)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($actionName)
    {
        return in_array($actionName, [self::PURCHASE, self::COMPLETE], true);
    }
}
