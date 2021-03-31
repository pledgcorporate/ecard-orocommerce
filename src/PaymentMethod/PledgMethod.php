<?php

namespace Pledg\Bundle\PaymentBundle\PaymentMethod;

use Oro\Bundle\PaymentBundle\Context\PaymentContextInterface;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Oro\Bundle\PaymentBundle\Method\PaymentMethodInterface;
use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfigInterface;

/**
 * @todo imlement method
 */
class PledgMethod implements PaymentMethodInterface
{
    /**
     * @var PledgConfigInterface
     */
    private $config;

    /**
     * @param PledgConfigInterface $config
     */
    public function __construct(PledgConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($action, PaymentTransaction $paymentTransaction)
    {
        $paymentTransaction->setAction(PaymentMethodInterface::INVOICE);
        $paymentTransaction->setActive(true);
        $paymentTransaction->setSuccessful(true);

        return [];
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
        return $actionName === self::PURCHASE;
    }
}
