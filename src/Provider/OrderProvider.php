<?php

namespace Pledg\Bundle\PaymentBundle\Provider;


use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

final class OrderProvider implements OrderProviderInterface
{
    /** @var DoctrineHelper */
    private $doctrineHelper;

    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    public function findByPaymentTransaction(PaymentTransaction $paymentTransaction): Order
    {
        /** @var Order $order */
        $order = $this->doctrineHelper->getEntityReference(
            $paymentTransaction->getEntityClass(),
            $paymentTransaction->getEntityIdentifier()
        );

        return $order;
    }
}
